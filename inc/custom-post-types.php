<?php

/**
 * Register Case Study Post Type
 *
 * The proof tier above Our Work. Feeds the homepage work section and, since
 * July 2026, its own pages: the archive at /case-studies/
 * (archive-case_study.php) and a narrative single per case study at
 * /case-studies/{slug}/ (single-case_study.php). with_front is off so the
 * /blog/ permalink base does not prefix the URLs. exclude_from_search is
 * load-bearing twice over: case studies support title only (search-result
 * cards would render as empty shells), and it is the flag that keeps case
 * studies out of the service term pages' main archive query (the insights
 * grid) — see vc_service_archive_query() in inc/actions.php.
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
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'has_archive'         => 'case-studies',
        'rewrite'             => array(
            'slug'       => 'case-studies',
            'with_front' => false,
        ),
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

/**
 * Register Project Post Type ("Our Work")
 *
 * The lighter portfolio tier under Case Studies. Feeds the homepage Our Work
 * section and, since July 2026, its own pages: the archive at /work/
 * (archive-project.php) and a showcase single per project at /work/{slug}/
 * (single-project.php). with_front is off so the /blog/ permalink base does
 * not prefix the URLs. exclude_from_search stays true: projects support
 * title only, so search-result cards would render as empty shells.
 */
add_action( 'init', 'project_post_type', 0 );
function project_post_type() {
    $labels = array(
        'name'               => _x( 'Our Work', 'Post Type General Name', 'vc' ),
        'singular_name'      => _x( 'Project', 'Post Type Singular Name', 'vc' ),
        'menu_name'          => __( 'Our Work', 'vc' ),
        'all_items'          => __( 'All projects', 'vc' ),
        'add_new'            => __( 'Add new', 'vc' ),
        'add_new_item'       => __( 'Add new project', 'vc' ),
        'edit_item'          => __( 'Edit project', 'vc' ),
        'update_item'        => __( 'Update project', 'vc' ),
        'view_item'          => __( 'View project', 'vc' ),
        'search_items'       => __( 'Search projects', 'vc' ),
        'not_found'          => __( 'Not Found', 'vc' ),
        'not_found_in_trash' => __( 'Not found in Trash', 'vc' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'has_archive'         => 'work',
        'rewrite'             => array(
            'slug'       => 'work',
            'with_front' => false,
        ),
        'hierarchical'        => false,
        'menu_position'       => 22,
        'menu_icon'           => 'dashicons-hammer',
        'supports'            => array( 'title' ),
    );
    register_post_type( 'project', $args );
}
