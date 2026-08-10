<?php

// Gravity Forms customisations for the multi-step enquiry form. Both hooks key
// on the form's `enquiry-form` cssClass, never a form id, so the form can be
// re-created or duplicated without touching theme code.

// The enquiry bundle's JS owns scrolling (page changes and confirmation);
// returning false also removes GF's anchor div and URL hash. Multi-step only:
// the single-step variant (`enquiry-form--simple`) keeps GF's native
// confirmation scroll so it works on any page without the bundle.
add_filter( 'gform_confirmation_anchor', 'vc_enquiry_disable_gf_scroll', 10, 2 );
function vc_enquiry_disable_gf_scroll( $anchor, $form ) {
	if ( strpos( (string) rgar( $form, 'cssClass' ), 'enquiry-form' ) !== false && ! empty( $form['pagination'] ) ) {
		return false;
	}
	return $anchor;
}

// Suppress GF's default AJAX spinner GIF on every form. The theme renders its
// own spinner over the pressed button (assets/js/global/form-loading.js +
// common/_form-loading.scss); GF's default is a footer sibling that shifts the
// button. Returning empty stops the image; the CSS `display:none` backs it up.
add_filter( 'gform_ajax_spinner_url', '__return_empty_string' );

// Service SVG watermarks on the picker cards (the corner-crop treatment from
// template-parts/service-card.php). Keyed on choice value so reordering or
// inserting choices never mismatches an icon; `something-else` stays plain.
add_filter( 'gform_field_choice_markup_pre_render', 'vc_enquiry_service_choice_icons', 10, 4 );
function vc_enquiry_service_choice_icons( $choice_markup, $choice, $field, $value ) {
	if ( $field->type !== 'checkbox' || strpos( (string) $field->cssClass, 'service-picker' ) === false ) {
		return $choice_markup;
	}
	if ( is_admin() || GFCommon::is_form_editor() ) {
		return $choice_markup;
	}

	// Read the watermark from the service term itself (the same `icon` field the
	// service cards use), keyed on the choice value (the term slug), so it stays
	// correct for any current or future pillar. `something-else` has no term and
	// stays plain.
	$slug = isset( $choice['value'] ) ? $choice['value'] : '';
	if ( ! $slug || 'something-else' === $slug || ! function_exists( 'get_field' ) ) {
		return $choice_markup;
	}

	$term = get_term_by( 'slug', $slug, 'service' );
	if ( ! $term || is_wp_error( $term ) ) {
		return $choice_markup;
	}

	$file = get_field( 'icon', 'service_' . $term->term_id );
	if ( ! $file ) {
		return $choice_markup;
	}

	$img = '<img class="choice-card-icon" src="' . esc_url( VC_TEMPLATE_URI . '/assets/images/icons/services/' . $file ) . '" alt="" aria-hidden="true" loading="lazy">';

	return str_replace( '</label>', $img . '</label>', $choice_markup );
}

// -----------------------------------------------------------------------------
// Dynamic service picker (step 1 of the enquiry form)
//
// The picker's choices are the current service pillars, read live from the
// taxonomy (Global Settings > Service List order) exactly like the mega menu,
// footer and homepage rail — so the form never drifts when services change.
// The choices are NOT stored on the form: they are injected at render, and
// again at validation and submission so the server collects and saves the same
// list. Keyed on the `service-picker` cssClass, never a form id.
//
// The same pass remaps the three pillar slugs renamed in the July 2026
// restructure wherever step-2 conditional logic still references an old value,
// so the "current website" / "new or redesign" fields keep firing.
// -----------------------------------------------------------------------------

add_filter( 'gform_pre_render', 'vc_enquiry_populate_service_picker' );
add_filter( 'gform_pre_validation', 'vc_enquiry_populate_service_picker' );
add_filter( 'gform_pre_submission_filter', 'vc_enquiry_populate_service_picker' );
add_filter( 'gform_admin_pre_render', 'vc_enquiry_populate_service_picker' );
function vc_enquiry_populate_service_picker( $form ) {
	if ( empty( $form['fields'] ) || ! function_exists( 'vc_ordered_services' ) ) {
		return $form;
	}

	// Leave the form editor showing whatever choices are stored, so opening and
	// saving the form never bakes these dynamic choices into the database. The
	// entry-detail screen (not the editor) still gets the live list, so stored
	// entries render with the right service labels.
	if ( class_exists( 'GFCommon' ) && GFCommon::is_form_editor() ) {
		return $form;
	}

	// The three pillars renamed in the restructure; any step-2 conditional rule
	// still keyed on an old value is remapped to the live slug below.
	$renamed = [
		'digital-marketing' => 'strategy-analytics',
		'paid-search-ppc'   => 'paid-media',
		'content-creation'  => 'content-marketing',
	];

	$picker_id = 0;

	foreach ( $form['fields'] as $field ) {
		if ( $field->type !== 'checkbox' || strpos( (string) $field->cssClass, 'service-picker' ) === false ) {
			continue;
		}
		$picker_id = (int) $field->id;

		$choices = [];
		$inputs  = [];
		$n       = 0;

		foreach ( vc_ordered_services() as $term ) {
			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}
			// The stored choices use plain ampersands; decode so GF's own
			// escaping renders "SEO & AI Search", not "SEO &amp; AI Search".
			$label     = html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' );
			$choices[] = [ 'text' => $label, 'value' => $term->slug, 'isSelected' => false ];
			$inputs[]  = [ 'id' => $field->id . '.' . vc_gf_input_suffix( ++$n ), 'label' => $label ];
		}

		// Keep the catch-all as the final option.
		$choices[] = [ 'text' => 'Something else', 'value' => 'something-else', 'isSelected' => false ];
		$inputs[]  = [ 'id' => $field->id . '.' . vc_gf_input_suffix( ++$n ), 'label' => 'Something else' ];

		$field->choices = $choices;
		$field->inputs  = $inputs;
	}

	// Remap renamed slugs in every field's conditional logic that depends on the
	// picker, so hidden/shown step-2 fields track the new pillar values.
	if ( $picker_id ) {
		foreach ( $form['fields'] as $field ) {
			if ( empty( $field->conditionalLogic['rules'] ) ) {
				continue;
			}
			foreach ( $field->conditionalLogic['rules'] as &$rule ) {
				if ( (int) $rule['fieldId'] === $picker_id && isset( $renamed[ $rule['value'] ] ) ) {
					$rule['value'] = $renamed[ $rule['value'] ];
				}
			}
			unset( $rule );
		}
	}

	return $form;
}

// Gravity Forms checkbox input suffixes skip every multiple of 10 (a legacy
// quirk: input 1.10 collides with 1.1). Map a 1-based choice position to its
// valid suffix — identity for the first nine, then 11, 12 … — so value
// collection and entry meta keys match how GF itself numbers the inputs.
function vc_gf_input_suffix( $position ) {
	$suffix = 0;
	$count  = 0;
	for ( $n = 1; $count < $position; $n++ ) {
		if ( $n % 10 === 0 ) {
			continue;
		}
		$count++;
		$suffix = $n;
	}
	return $suffix;
}

// -----------------------------------------------------------------------------
// Editable form selection
//
// Each page that embeds a form carries a "Select Form" ACF dropdown so the form
// can be swapped from the admin without touching a template. The choices are
// populated live from Gravity Forms (below), so the list always matches the
// forms that actually exist. Leaving a picker on the blank "Use default" option
// keeps the form the template shipped with, so existing pages never change
// until an editor picks a different form on purpose.
// -----------------------------------------------------------------------------

// Render a form by its editor-chosen id, falling back to the template's default.
// $default_id is the form the section shipped with; $acf_id/$field say where to
// read the override from ('options' + 'newsletter_form_id' for the site-wide
// newsletter, the current post + 'vc_page_form' for a page). Guards against
// Gravity Forms being inactive so a template never fatals.
function vc_render_form( $default_id, $acf_id = false, $field = 'vc_page_form' ) {
	$selected = function_exists( 'get_field' ) ? get_field( $field, $acf_id ) : '';
	$form_id  = $selected ? (int) $selected : (int) $default_id;

	if ( ! $form_id || ! class_exists( 'GFAPI' ) ) {
		return;
	}

	echo do_shortcode( sprintf( '[gravityform id="%d" title="false" description="false" ajax="true"]', $form_id ) );
}

// The two picker field groups, registered in PHP (not acf-json) so they go live
// immediately: the page groups and the Global Settings options page already
// exist in the ACF database, and a hand-edited JSON file would sit behind a
// manual "Sync" click before appearing.
add_action( 'acf/init', 'vc_register_form_picker_fields' );
function vc_register_form_picker_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// Per-page picker: one saved value per page it appears on.
	acf_add_local_field_group( [
		'key'      => 'group_vc_form_settings',
		'title'    => 'Form Settings',
		'fields'   => [
			[
				'key'          => 'field_vc_page_form',
				'label'        => 'Select Form',
				'name'         => 'vc_page_form',
				'type'         => 'select',
				'instructions' => 'Choose which form appears in this page\'s contact section. Leave on "Use default" to keep the current form.',
				'choices'      => [],
				'allow_null'   => 1,
				'ui'           => 1,
				'placeholder'  => '— Use default —',
				'return_format' => 'value',
			],
		],
		'location' => [
			[ [ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ] ],
			[ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-contact-us.php' ] ],
			[ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-services-hub.php' ] ],
			[ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-free-website.php' ] ],
			[ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-landing-page.php' ] ],
		],
		'menu_order'      => 20,
		'position'        => 'side',
		'active'          => true,
		'description'     => '',
	] );

	// Site-wide newsletter picker on the Global Settings options page. Global
	// rather than per-post because the newsletter renders on every blog single.
	acf_add_local_field_group( [
		'key'      => 'group_vc_newsletter_form',
		'title'    => 'Newsletter Form',
		'fields'   => [
			[
				'key'          => 'field_newsletter_form_id',
				'label'        => 'Newsletter Form',
				'name'         => 'newsletter_form_id',
				'type'         => 'select',
				'instructions' => 'Choose the form shown in the blog article sidebar. Leave on "Use default" to keep the current newsletter form.',
				'choices'      => [],
				'allow_null'   => 1,
				'ui'           => 1,
				'placeholder'  => '— Use default —',
				'return_format' => 'value',
			],
		],
		'location' => [
			[ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'global-settings' ] ],
		],
		'menu_order'  => 20,
		'active'      => true,
		'description' => '',
	] );
}

// Populate both pickers with the live Gravity Forms list. Keyed on field key so
// the same loader serves the page picker and the newsletter picker.
add_filter( 'acf/load_field/key=field_vc_page_form', 'vc_load_gravity_form_choices' );
add_filter( 'acf/load_field/key=field_newsletter_form_id', 'vc_load_gravity_form_choices' );
function vc_load_gravity_form_choices( $field ) {
	$field['choices'] = [];

	if ( class_exists( 'GFAPI' ) ) {
		foreach ( GFAPI::get_forms() as $form ) {
			$field['choices'][ (int) $form['id'] ] = $form['title'] . ' (ID ' . $form['id'] . ')';
		}
	}

	return $field;
}
