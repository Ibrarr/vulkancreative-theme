<?php

/**
 * Initial setup
 */
add_action( 'after_setup_theme', 'vc_setup' );
function vc_setup() {
	load_theme_textdomain( 'vc', get_template_directory() . '/languages' );
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
	// One menu serves every page; anchor items ("/#why") are flattened to
	// in-page anchors on the front page by a filter in inc/filters.php.
	register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'vc' ) ) );
}

/**
 * Register main stylesheet and jquery
 */
add_action( 'wp_enqueue_scripts', 'vc_enqueue' );
function vc_enqueue() {
	wp_enqueue_style( 'vc-style', get_stylesheet_uri() );
    wp_enqueue_script( 'jquery', false, [], false, true );
}

/**
 * Add script to the footer to check which device/browser the user is using
 */
add_action( 'wp_footer', 'vc_footer' );
function vc_footer() {
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
if ( ! function_exists( 'vc_wp_body_open' ) ) {
	function vc_wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Adds a pingback link to the header if the post supports pingbacks.
 */
add_action( 'wp_head', 'vc_pingback_header' );
function vc_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

/**
 * Register menus
 */
register_nav_menus( array( 'footer-menu' => esc_html__( 'Footer Menu', 'vc' ) ) );

/**
 * The service taxonomy moved from /service/{slug} to /services/{slug} (under
 * the Services hub page), so the old base 301s to the same term's new home,
 * and retired pillar slugs (vc_service_slug_aliases()) 301 to their
 * replacement. Priority 0 so it runs ahead of any other template_redirect
 * handling.
 */
add_action( 'template_redirect', 'vc_service_slug_redirect', 0 );
function vc_service_slug_redirect() {
	$path = wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
	if ( ! $path ) {
		return;
	}
	$renamed = vc_service_slug_aliases();

	// Legacy singular base: /service/{slug} -> the term's canonical URL,
	// through the alias map too.
	if ( preg_match( '#^/service/([^/]+)/?$#', $path, $matches ) ) {
		$slug = sanitize_title( $matches[1] );
		$term = get_term_by( 'slug', $renamed[ $slug ] ?? $slug, 'service' );
		if ( $term && ! is_wp_error( $term ) ) {
			wp_safe_redirect( get_term_link( $term ), 301 );
			exit;
		}
		return;
	}

	// /services/{pillar}/ and /services/{pillar}/{child}/. The rewrite is
	// hierarchical, so WP resolves a child by its LAST segment whatever the
	// first says: /services/content-marketing/copywriting/ would render the
	// copywriting page 200 at a stale URL. 301 to the child's canonical link
	// when the first segment is not its live parent; a bare or unresolvable
	// path under a renamed pillar goes to the pillar itself. Canonical URLs
	// never match and fall through.
	if ( ! preg_match( '#^/services/([^/]+)(?:/([^/]+))?/?$#', $path, $m ) ) {
		return;
	}
	$first  = sanitize_title( $m[1] );
	$second = isset( $m[2] ) ? sanitize_title( $m[2] ) : '';
	$target = null;

	if ( $second ) {
		$child = get_term_by( 'slug', $second, 'service' );
		if ( $child && ! is_wp_error( $child ) && $child->parent ) {
			$parent = get_term( (int) $child->parent, 'service' );
			if ( $parent && ! is_wp_error( $parent ) && $parent->slug !== $first ) {
				$target = $child;
			}
		}
	}
	if ( ! $target && isset( $renamed[ $first ] ) ) {
		$target = get_term_by( 'slug', $renamed[ $first ], 'service' );
	}
	if ( $target && ! is_wp_error( $target ) ) {
		$link = get_term_link( $target );
		if ( ! is_wp_error( $link ) ) {
			wp_safe_redirect( $link, 301 );
			exit;
		}
	}
}

/**
 * Renamed blog categories: /blog/category/{old}/... -> the live category,
 * keeping any trailing path (pagination, feed). Aug 2026: "Content Marketing"
 * became "Content & Social" alongside the service pillar of the same name.
 */
add_action( 'template_redirect', 'vc_category_slug_redirect', 0 );
function vc_category_slug_redirect() {
	$path = wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
	if ( ! $path || ! preg_match( '#^/blog/category/([^/]+)(/.*)?$#', $path, $m ) ) {
		return;
	}
	$renamed = array(
		'content-marketing' => 'content-social',
	);
	$slug = sanitize_title( $m[1] );
	if ( ! isset( $renamed[ $slug ] ) ) {
		return;
	}
	$term = get_term_by( 'slug', $renamed[ $slug ], 'category' );
	if ( ! $term || is_wp_error( $term ) ) {
		return;
	}
	$link = get_term_link( $term );
	if ( is_wp_error( $link ) ) {
		return;
	}
	$rest = isset( $m[2] ) ? ltrim( $m[2], '/' ) : '';
	wp_safe_redirect( trailingslashit( $link ) . $rest, 301 );
	exit;
}

/**
 * Service term pages: the main archive query only feeds the small "insights"
 * grid, so cap it to the three newest posts and skip the pagination count.
 */
add_action( 'pre_get_posts', 'vc_service_archive_query' );
function vc_service_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_tax( 'service' ) ) {
		return;
	}
	// The work and case-studies archives strip ?service= into a private var
	// (inc/filters.php) before the flags are parsed, so their queries should
	// never land here; guard anyway so the cap can never eat those grids.
	if ( in_array( $query->get( 'post_type' ), array( 'project', 'case_study' ), true ) ) {
		return;
	}
	// Pin the insights grid to blog posts. Projects and case studies carry
	// service terms too but stay out of tax archives via exclude_from_search;
	// pinning here means the grid no longer silently depends on that flag.
	$query->set( 'post_type', 'post' );
	$query->set( 'posts_per_page', 3 );
	$query->set( 'ignore_sticky_posts', true );
	$query->set( 'no_found_rows', true );
}

/**
 * Work archive: every published project renders on /work/ in one pass (the
 * grid filters in place, no pagination), so uncap the main query, order it
 * newest first and skip the pagination count.
 */
add_action( 'pre_get_posts', 'vc_work_archive_query' );
function vc_work_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'project' ) ) {
		return;
	}
	$query->set( 'posts_per_page', -1 );
	$query->set( 'ignore_sticky_posts', true );
	$query->set( 'no_found_rows', true );
	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
}

/**
 * Case studies archive: every published case study renders on /case-studies/
 * in one pass (the grid filters in place, no pagination), so uncap the main
 * query, order it newest first and skip the pagination count.
 */
add_action( 'pre_get_posts', 'vc_case_study_archive_query' );
function vc_case_study_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'case_study' ) ) {
		return;
	}
	$query->set( 'posts_per_page', -1 );
	$query->set( 'ignore_sticky_posts', true );
	$query->set( 'no_found_rows', true );
	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
}

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

/**
 * Stop WP Site Icon from outputting its own tags
 */
remove_action('wp_head', 'wp_site_icon', 99);
add_action('wp_head', function () {
    $base = VC_TEMPLATE_URI . '/assets/images/logos/icon';

    $svg      = esc_url("$base/favicon.svg");
    $ico      = esc_url("$base/favicon.ico");
    $png16    = esc_url("$base/favicon-16x16.png");
    $png32    = esc_url("$base/favicon-32x32.png");
    $png96    = esc_url("$base/favicon-96x96.png");
    $png192   = esc_url("$base/android-chrome-192x192.png");
    $png512   = esc_url("$base/android-chrome-512x512.png");
    $apple180 = esc_url("$base/apple-touch-icon.png");
    $manifest = esc_url('/site.webmanifest');
    ?>

    <!-- Modern SVG -->
    <link rel="icon" type="image/svg+xml" href="<?php echo $svg; ?>" sizes="any">

    <!-- Desktop/Android PNGs -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $png16; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $png32; ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $png96; ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $png192; ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo $png512; ?>">

    <!-- Single ICO (drop the duplicate/shortcut line) -->
    <link rel="icon" type="image/x-icon" href="<?php echo $ico; ?>">

    <!-- iOS -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $apple180; ?>">
    <meta name="apple-mobile-web-app-title" content="Vulkan Creative">
    <meta name="application-name" content="Vulkan Creative">

    <!-- Colours -->
    <meta name="theme-color" content="#FF3B30">

    <!-- Manifest -->
    <link rel="manifest" href="<?php echo $manifest; ?>">
    <?php
}, 1);
