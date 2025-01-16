<?php

/**
 * Register Services Taxonomy
 */
add_action( 'init', 'services_taxonomy', 0 );
function services_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Services', 'Taxonomy General Name', 'vc' ),
        'singular_name'              => _x( 'Service', 'Taxonomy Singular Name', 'vc' ),
        'menu_name'                  => __( 'Services', 'vc' ),
        'all_items'                  => __( 'All services', 'vc' ),
        'parent_item'                => __( 'Parent service', 'vc' ),
        'parent_item_colon'          => __( 'Parent service:', 'vc' ),
        'new_item_name'              => __( 'New service Name', 'vc' ),
        'add_new_item'               => __( 'Add New service', 'vc' ),
        'edit_item'                  => __( 'Edit service', 'vc' ),
        'update_item'                => __( 'Update service', 'vc' ),
        'view_item'                  => __( 'View service', 'vc' ),
        'search_items'               => __( 'Search services', 'vc' ),
        'not_found'                  => __( 'Not Found', 'vc' ),
        'no_terms'                   => __( 'No services', 'vc' ),
        'items_list'                 => __( 'Services list', 'vc' ),
        'items_list_navigation'      => __( 'Services list navigation', 'vc' ),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug'          => 'service',
            'with_front'    => false,
            'hierarchical'  => true,
        ),
    );
    register_taxonomy( 'service', array( 'post' ), $args );
}
