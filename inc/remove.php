<?php

/**
 * Remove default tag taxonomy
 */
add_action('init', 'remove_taxonomy');
function remove_taxonomy(){
	unregister_taxonomy_for_object_type('post_tag', 'post');
}

/**
 * Remove comments
 */
add_action('admin_init', 'disable_comments_support');
function disable_comments_support() {
	// Post types for which to disable comments
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}

/**
 * Remove comments from menu
 */
add_action('wp_before_admin_bar_render', 'remove_comments_from_admin_bar');
function remove_comments_from_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}

/**
 * Remove comments page
 */
add_action('admin_menu', 'remove_comments_admin_menu');
function remove_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}

/**
 * Front end: drop what a classic-editor theme never uses.
 *
 * The block editor is off site-wide, so the block library, global styles and
 * classic-theme styles only add render-blocking CSS to <head>; the emoji
 * detection script and styles go for the same reason.
 */
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );

add_action( 'wp_enqueue_scripts', 'vc_dequeue_block_styles', 20 );
function vc_dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}

/**
 * jQuery on the front end: no jquery-migrate, and printed in the footer.
 *
 * The theme no longer asks for jQuery at all; it arrives only as a Gravity
 * Forms dependency, on pages that render a form. Left at its default it printed
 * in <head> and blocked rendering there.
 */
add_action( 'wp_default_scripts', 'vc_front_end_jquery' );
function vc_front_end_jquery( $scripts ) {
	if ( is_admin() || empty( $scripts->registered['jquery'] ) ) {
		return;
	}
	$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, [ 'jquery-migrate' ] );
	$scripts->add_data( 'jquery', 'group', 1 );
	$scripts->add_data( 'jquery-core', 'group', 1 );
}

