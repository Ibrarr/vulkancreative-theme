<?php

// Global Service list: the single control for the six parent services, their
// order and where each one appears (header mega menu, footer, homepage rail,
// services hub), plus, inside each row, the order of that pillar's child
// services (Aug 2026). Registered in PHP on the Global Settings options page for
// the same reason as inc/gravity-forms.php: the options page already exists in
// the ACF database, so a hand-edited acf-json file would sit behind a manual
// Sync. Read it through vc_ordered_services() and vc_service_children() in
// inc/template-functions.php; a child left out of its row still renders, after
// the listed ones, alphabetically.
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
				'instructions' => 'Set the order of the main services by dragging the rows, and tick where each one shows. Inside each row, list its child services in the order they should appear on the service page and in the menu; any child left out is added after them alphabetically.',
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
					[
						'key'          => 'field_vc_service_list_children',
						'label'        => 'Child services (order)',
						'name'         => 'children',
						'type'         => 'repeater',
						'instructions' => 'Drag to set the order of this service\'s child services on its page, in the menu and in a child page\'s related strip. Any child not listed here is added after these, alphabetically, so nothing ever disappears.',
						'layout'       => 'table',
						'button_label' => 'Add child service',
						'min'          => 0,
						'max'          => 0,
						'sub_fields'   => [
							[
								'key'           => 'field_vc_service_list_child_term',
								'label'         => 'Child service',
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
							],
						],
					],
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
// list only ever holds the pillars; children go in each row's own sub-list.
add_filter( 'acf/fields/taxonomy/query/key=field_vc_service_list_term', 'vc_service_list_top_level_only' );
function vc_service_list_top_level_only( $args ) {
	$args['parent'] = 0;
	return $args;
}

// The child picker shows children only (top-level ids excluded). ACF applies
// this before the AJAX 'include' for an already-selected value, so a saved pick
// always resolves. A pick from another pillar is harmless: vc_service_children()
// only honours ids that really are children of the row's pillar.
add_filter( 'acf/fields/taxonomy/query/key=field_vc_service_list_child_term', 'vc_service_list_children_only' );
function vc_service_list_children_only( $args ) {
	$top = get_terms( [ 'taxonomy' => 'service', 'hide_empty' => false, 'parent' => 0, 'fields' => 'ids' ] );
	if ( ! is_wp_error( $top ) && $top ) {
		$args['exclude'] = $top;
	}
	return $args;
}

// Label each child pick "Pillar › Child" so the dropdown reads clearly once the
// parents are excluded (ACF's default would be a flat alphabetical list).
add_filter( 'acf/fields/taxonomy/result/key=field_vc_service_list_child_term', 'vc_service_list_child_label', 10, 2 );
function vc_service_list_child_label( $title, $term ) {
	$parent = ( $term && $term->parent ) ? get_term( (int) $term->parent, 'service' ) : null;
	$label  = ( $parent && ! is_wp_error( $parent ) ) ? $parent->name . ' › ' . $term->name : $title;
	return html_entity_decode( $label, ENT_QUOTES, 'UTF-8' );
}

// The Service Page "Related Services" picker (sv_related_services in
// acf-json/service-page.json) never offers the term being edited.
add_filter( 'acf/fields/taxonomy/query/key=field_680b0260', 'vc_service_related_exclude_self', 10, 3 );
function vc_service_related_exclude_self( $args, $field, $post_id ) {
	$decoded = function_exists( 'acf_decode_post_id' ) ? acf_decode_post_id( $post_id ) : array();
	if ( ! empty( $decoded['type'] ) && 'term' === $decoded['type'] && ! empty( $decoded['id'] ) ) {
		$args['exclude'] = array( (int) $decoded['id'] );
	}
	return $args;
}
