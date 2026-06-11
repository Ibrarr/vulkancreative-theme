<?php

/**
 * Register Case Study Post Type
 *
 * Admin-only content consumed by the homepage work section.
 * No single pages or archive for now.
 */
add_action( 'init', 'case_study_post_type', 0 );
function case_study_post_type() {
    $labels = array(
        'name'               => _x( 'Case Studies', 'Post Type General Name', 'vc' ),
        'singular_name'      => _x( 'Case Study', 'Post Type Singular Name', 'vc' ),
        'menu_name'          => __( 'Case Studies', 'vc' ),
        'all_items'          => __( 'All case studies', 'vc' ),
        'add_new'            => __( 'Add new', 'vc' ),
        'add_new_item'       => __( 'Add new case study', 'vc' ),
        'edit_item'          => __( 'Edit case study', 'vc' ),
        'update_item'        => __( 'Update case study', 'vc' ),
        'view_item'          => __( 'View case study', 'vc' ),
        'search_items'       => __( 'Search case studies', 'vc' ),
        'not_found'          => __( 'Not Found', 'vc' ),
        'not_found_in_trash' => __( 'Not found in Trash', 'vc' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array( 'title' ),
    );
    register_post_type( 'case_study', $args );
}

/**
 * Register Testimonial Post Type
 *
 * Admin-only content consumed by the homepage testimonials section.
 * No single pages or archive.
 */
add_action( 'init', 'testimonial_post_type', 0 );
function testimonial_post_type() {
    $labels = array(
        'name'               => _x( 'Testimonials', 'Post Type General Name', 'vc' ),
        'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'vc' ),
        'menu_name'          => __( 'Testimonials', 'vc' ),
        'all_items'          => __( 'All testimonials', 'vc' ),
        'add_new'            => __( 'Add new', 'vc' ),
        'add_new_item'       => __( 'Add new testimonial', 'vc' ),
        'edit_item'          => __( 'Edit testimonial', 'vc' ),
        'update_item'        => __( 'Update testimonial', 'vc' ),
        'view_item'          => __( 'View testimonial', 'vc' ),
        'search_items'       => __( 'Search testimonials', 'vc' ),
        'not_found'          => __( 'Not Found', 'vc' ),
        'not_found_in_trash' => __( 'Not found in Trash', 'vc' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'hierarchical'        => false,
        'menu_position'       => 21,
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => array( 'title' ),
    );
    register_post_type( 'testimonial', $args );
}
