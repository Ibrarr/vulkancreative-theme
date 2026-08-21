<?php

// Trust signals: the partner badge strip and the press "As featured in"
// credits, both on the Global Settings options page. Registered in PHP for
// the same reason as inc/service-list-fields.php: the options page already
// exists in the ACF database, so a hand-edited acf-json file would sit behind
// a manual Sync. The badges render through template-parts/partner-logos.php
// (homepage why band, footer trust row); the press logos through the footer
// trust row and the About press credit line.
add_action( 'acf/init', 'vc_register_trust_signals_fields' );
function vc_register_trust_signals_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( [
		'key'    => 'group_vc_partner_logos',
		'title'  => 'Partner Logos',
		'fields' => [
			[
				'key'           => 'field_vc_partner_logos_heading',
				'label'         => 'Strip Label',
				'name'          => 'partner_logos_heading',
				'type'          => 'text',
				'instructions'  => 'The line above the badges on the homepage strip.',
				'default_value' => 'Official partners of the platforms we build on',
				'wrapper'       => [ 'width' => '60' ],
			],
			[
				'key'           => 'field_vc_partner_logos_footer_label',
				'label'         => 'Footer Label',
				'name'          => 'partner_logos_footer_label',
				'type'          => 'text',
				'instructions'  => 'The short label above the badges in the footer.',
				'default_value' => 'Official partners',
				'wrapper'       => [ 'width' => '40' ],
			],
			[
				'key'          => 'field_vc_partner_logos',
				'label'        => 'Partners',
				'name'         => 'partner_logos',
				'type'         => 'repeater',
				'instructions' => 'Official partner badges, shown as issued (never recoloured) on the homepage why band and in the footer, both dark surfaces.',
				'layout'       => 'table',
				'button_label' => 'Add Partner',
				'sub_fields'   => [
					[
						'key'           => 'field_vc_partner_logo',
						'label'         => 'Badge',
						'name'          => 'logo',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'library'       => 'all',
						'required'      => 1,
					],
					[
						'key'   => 'field_vc_partner_name',
						'label' => 'Name',
						'name'  => 'name',
						'type'  => 'text',
					],
					[
						'key'   => 'field_vc_partner_url',
						'label' => 'Link (optional)',
						'name'  => 'url',
						'type'  => 'url',
					],
				],
			],
		],
		'location' => [
			[ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'global-settings' ] ],
		],
		'menu_order'  => 11,
		'active'      => true,
		'description' => '',
	] );

	acf_add_local_field_group( [
		'key'    => 'group_vc_press_features',
		'title'  => 'Press Features',
		'fields' => [
			[
				'key'          => 'field_vc_press_features',
				'label'        => 'Publications',
				'name'         => 'press_features',
				'type'         => 'repeater',
				'instructions' => 'Titles for the footer "As featured in" credit; the first row\'s logo is also the About press section\'s fallback image. Empty list, no credit.',
				'layout'       => 'table',
				'button_label' => 'Add Publication',
				'sub_fields'   => [
					[
						'key'           => 'field_vc_press_logo',
						'label'         => 'Logo',
						'name'          => 'logo',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'library'       => 'all',
						'required'      => 1,
					],
					[
						'key'   => 'field_vc_press_name',
						'label' => 'Name',
						'name'  => 'name',
						'type'  => 'text',
					],
					[
						'key'   => 'field_vc_press_url',
						'label' => 'Link',
						'name'  => 'url',
						'type'  => 'url',
					],
				],
			],
		],
		'location' => [
			[ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'global-settings' ] ],
		],
		'menu_order'  => 12,
		'active'      => true,
		'description' => '',
	] );
}
