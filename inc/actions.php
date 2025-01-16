<?php

/**
 * Initial setup
 */
add_action( 'after_setup_theme', 'dc_setup' );
function dc_setup() {
	load_theme_textdomain( 'dc', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
	add_theme_support( 'woocommerce' );
	add_image_size( 'header-image', 1920, 1080 );
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1920;
	}
	register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'dc' ) ) );
}

/**
 * Register main stylesheet and jquery
 */
add_action( 'wp_enqueue_scripts', 'dc_enqueue' );
function dc_enqueue() {
	wp_enqueue_style( 'dc-style', get_stylesheet_uri() );
	wp_enqueue_script( 'jquery' );
}

/**
 * Add script to the footer to check which device/browser the user is using
 */
add_action( 'wp_footer', 'dc_footer' );
function dc_footer() {
	?>
    <script>
        jQuery(document).ready(function ($) {
            var deviceAgent = navigator.userAgent.toLowerCase();
            if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
                $("html").addClass("ios");
                $("html").addClass("mobile");
            }
            if (deviceAgent.match(/(Android)/)) {
                $("html").addClass("android");
                $("html").addClass("mobile");
            }
            if (navigator.userAgent.search("MSIE") >= 0) {
                $("html").addClass("ie");
            } else if (navigator.userAgent.search("Chrome") >= 0) {
                $("html").addClass("chrome");
            } else if (navigator.userAgent.search("Firefox") >= 0) {
                $("html").addClass("firefox");
            } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
                $("html").addClass("safari");
            } else if (navigator.userAgent.search("Opera") >= 0) {
                $("html").addClass("opera");
            }
        });
    </script>
	<?php
}

/**
 * Wrapper function to execute the 'wp_body_open' action.
 */
if ( ! function_exists( 'dc_wp_body_open' ) ) {
	function dc_wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Adds a pingback link to the header if the post supports pingbacks.
 */
add_action( 'wp_head', 'dc_pingback_header' );
function dc_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

/**
 * Register menus
 */
register_nav_menus( array( 'footer-menu' => esc_html__( 'Footer Menu', 'dc' ) ) );
register_nav_menus( array( 'footer-menu-practise-areas' => esc_html__( 'Footer Menu Practise Areas', 'dc' ) ) );

/**
 * Setup 404 page
 */
add_filter( 'template_include', 'custom_404_redirect' );
function custom_404_redirect( $template ) {
	if ( is_404() ) {
		status_header( 404 );
		$generic_404_template = locate_template( '404.php' );
		if ( $generic_404_template ) {
			return $generic_404_template;
		}
	}

	return $template;
}

/**
 * Redirect searches to search page
 */
//add_action( 'template_redirect', 'wpb_change_search_url' );
//function wpb_change_search_url() {
//	if ( is_search() && empty( $_GET['s'] ) ) {
//		wp_redirect( home_url( "/search/" ) );
//		exit();
//	} else if ( is_search() && ! empty( $_GET['s'] ) ) {
//		wp_redirect( home_url( "/search/" ) . '?term=' . urlencode( get_query_var( 's' ) ) );
//		exit();
//	}
//}

/**
 * Remove content editor on default post type
 */
add_action( 'init', 'disable_content_editor_on_post' );
function disable_content_editor_on_post() {
	remove_post_type_support( 'post', 'editor' );
}