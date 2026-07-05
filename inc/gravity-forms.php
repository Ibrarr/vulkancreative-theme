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
