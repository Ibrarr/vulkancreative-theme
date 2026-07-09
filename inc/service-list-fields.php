<?php

// Global Service list: the single control for the six parent services — their
// order and where each one appears (header mega menu, footer, homepage rail,
// services hub). Registered in PHP on the Global Settings options page for the
// same reason as inc/gravity-forms.php: the options page already exists in the
// ACF database, so a hand-edited acf-json file would sit behind a manual Sync.
// Read it through vc_ordered_services() in inc/template-functions.php. Child
// services are not listed here — they render alphabetically under their parent.
add_action( 'acf/init', 'vc_register_service_list_fields' );
function vc_register_service_list_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$toggle = function ( $key, $name, $label ) {
		return [
			'key'           => $key,
			'label'         => $label,
			'name'          => $name,
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 1,
			'wrapper'       => [ 'width' => '15' ],
		];
	};

	acf_add_local_field_group( [
		'key'    => 'group_vc_service_list',
		'title'  => 'Service List',
		'fields' => [
			[
				'key'          => 'field_vc_service_list',
				'label'        => 'Services',
				'name'         => 'service_list',
				'type'         => 'repeater',
				'instructions' => 'Set the order of the main services by dragging the rows, and tick where each one shows. Child services are listed automatically in alphabetical order on their parent page.',
				'layout'       => 'block',
				'button_label' => 'Add Service',
				'sub_fields'   => [
					[
						'key'           => 'field_vc_service_list_term',
						'label'         => 'Service',
						'name'          => 'service',
						'type'          => 'taxonomy',
						'taxonomy'      => 'service',
						'field_type'    => 'select',
						'add_term'      => 0,
						'save_terms'    => 0,
						'load_terms'    => 0,
						'return_format' => 'id',
						'multiple'      => 0,
						'allow_null'    => 0,
						'wrapper'       => [ 'width' => '40' ],
					],
					$toggle( 'field_vc_service_list_menu', 'show_menu', 'Menu' ),
					$toggle( 'field_vc_service_list_footer', 'show_footer', 'Footer' ),
					$toggle( 'field_vc_service_list_homepage', 'show_homepage', 'Homepage' ),
					$toggle( 'field_vc_service_list_hub', 'show_hub', 'Services hub' ),
				],
			],
		],
		'location' => [
			[ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'global-settings' ] ],
		],
		'menu_order'  => 5,
		'active'      => true,
		'description' => '',
	] );
}

// Restrict the Service List term picker to top-level (parent) services, so the
// list only ever holds the pillars — children are handled automatically.
add_filter( 'acf/fields/taxonomy/query/key=field_vc_service_list_term', 'vc_service_list_top_level_only' );
function vc_service_list_top_level_only( $args ) {
	$args['parent'] = 0;
	return $args;
}
