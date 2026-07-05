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

	$icons = [
		'web-design-development' => 'design-development.svg',
		'seo-ai-search'          => 'seo.svg',
		'digital-marketing'      => 'marketing.svg',
		'paid-search-ppc'        => 'paid-search-ppc.svg',
		'content-creation'       => 'content.svg',
		'branding'               => 'branding.svg',
	];

	$file = isset( $choice['value'], $icons[ $choice['value'] ] ) ? $icons[ $choice['value'] ] : '';
	if ( ! $file ) {
		return $choice_markup;
	}

	$img = '<img class="choice-card-icon" src="' . esc_url( VC_TEMPLATE_URI . '/assets/images/icons/services/' . $file ) . '" alt="" aria-hidden="true" loading="lazy">';

	return str_replace( '</label>', $img . '</label>', $choice_markup );
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
			[ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-your-business.php' ] ],
			[ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-free-website.php' ] ],
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
