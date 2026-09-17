<?php

add_action( 'wp_enqueue_scripts', 'add_custom_scripts' );
function add_custom_scripts() {
    wp_enqueue_style( 'site', VC_TEMPLATE_URI . mix('/css/app.css'), [], null, 'all' );

    if ( is_front_page() ) {
        vc_enqueue_bundle( 'homepage' );
        // Tell webpack where to load lazily-imported chunks from (the theme's dist dir),
        // otherwise dynamic imports resolve against the site root on WordPress.
        wp_add_inline_script( 'homepage', 'window.__vc_public_path=' . wp_json_encode( VC_TEMPLATE_URI . '/dist/' ) . ';', 'before' );
    }

    // is_singular('post'), not is_single(): the latter is true for CPT
    // singles too and would leak the blog bundle onto project pages.
    if ( is_singular( 'post' ) ) {
        vc_enqueue_bundle( 'single-blog' );
    }

    if ( is_home() || is_category() || is_search() ) {
        vc_enqueue_bundle( 'archive-blog' );
        wp_localize_script( 'archive-blog', 'vcInsights', [
            'rest'    => esc_url_raw( rest_url( 'vc/v1/insights' ) ),
            'blogUrl' => get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ),
        ] );
    }

    if ( is_author() ) {
        vc_enqueue_bundle( 'archive-author' );
    }

    if ( is_page_template( 'page-templates/page-free-website.php' ) ) {
        vc_enqueue_bundle( 'free-website' );
        // Form 7 carries the `enquiry-form` cssClass, so it gets the same
        // entrance, dataLayer events and confirmation focus handling the
        // landing template already loads (parity fix, Aug 2026).
        vc_enqueue_bundle( 'enquiry-form' );
    }

    if ( is_page_template( 'page-templates/page-landing-page.php' ) ) {
        vc_enqueue_bundle( 'landing' );
        // A landing CTA block can embed the multi-step enquiry form (id 5); its
        // bundle/SCSS key on the `enquiry-form` cssClass, so this covers either
        // the multi-step or a single-step form with no extra wiring.
        vc_enqueue_bundle( 'enquiry-form' );
    }

    if ( is_page_template( 'page-templates/page-contact-us.php' ) ) {
        vc_enqueue_bundle( 'contact' );
        // Multi-step enquiry form (form id 5). When the form moves to another
        // template, extend this condition — the bundle and SCSS are otherwise
        // self-contained (both key on the form's `enquiry-form` cssClass).
        vc_enqueue_bundle( 'enquiry-form' );
    }

    if ( is_page_template( 'page-templates/page-about-us.php' ) ) {
        vc_enqueue_bundle( 'about' );
    }

    if ( is_page_template( 'page-templates/page-services-hub.php' ) ) {
        vc_enqueue_bundle( 'services-hub' );
    }

    if ( is_tax( 'service' ) ) {
        vc_enqueue_bundle( 'service' );
    }

    if ( is_post_type_archive( 'project' ) || is_singular( 'project' ) ) {
        vc_enqueue_bundle( 'project' );
    }

    if ( is_post_type_archive( 'case_study' ) || is_singular( 'case_study' ) ) {
        vc_enqueue_bundle( 'case-study' );
    }

    vc_enqueue_bundle( 'global' );
    vc_enqueue_bundle( 'header' );
    // No footer bundle: the wordmark's rise is a CSS scroll-driven animation.
}

/**
 * Enqueue one theme bundle from dist/js, deferred from <head>.
 *
 * Deferred-in-head starts the download alongside the HTML parse and runs the
 * bundles in order once parsing finishes, ahead of DOMContentLoaded, which is
 * the event every module waits for. No bundle depends on jQuery: the modules
 * that talk to Gravity Forms guard on window.jQuery, and Gravity Forms brings
 * jQuery itself wherever a form renders.
 */
function vc_enqueue_bundle( string $handle ) {
    wp_enqueue_script(
        $handle,
        VC_TEMPLATE_URI . mix( "/js/{$handle}.js" ),
        [],
        null,
        [ 'strategy' => 'defer', 'in_footer' => false ]
    );
}

function mix( string $path ) {
    $manifestPath = VC_TEMPLATE_DIR . '/dist/mix-manifest.json';
    if ( file_exists( $manifestPath ) ) {
        $manifest = json_decode( file_get_contents( $manifestPath ), true );
        // If the manifest has an entry for $path, prefix it with '/dist'
        if ( isset( $manifest[ $path ] ) ) {
            return '/dist' . $manifest[ $path ];
        }
    }
    // Fallback to the un-versioned file under /dist
    return '/dist' . $path;
}

/**
 * Pages that render a Gravity Form, by conditional tag. Asset decisions made
 * in <head> (preconnects) cannot wait for the template to render the form, so
 * this mirrors the vc_render_form() call sites.
 */
function vc_page_has_form() {
    return is_front_page()
        || is_tax( 'service' )
        || is_singular( 'post' )
        || is_page_template( [
            'page-templates/page-services-hub.php',
            'page-templates/page-contact-us.php',
            'page-templates/page-free-website.php',
            'page-templates/page-landing-page.php',
        ] );
}

/**
 * Open the third-party connections early: the consent banner on every page,
 * and reCAPTCHA's two origins where a form renders.
 */
add_filter( 'wp_resource_hints', 'vc_resource_hints', 10, 2 );
function vc_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' !== $relation_type ) {
        return $urls;
    }
    $urls[] = 'https://cdn-cookieyes.com';
    if ( vc_page_has_form() ) {
        $urls[] = 'https://www.google.com';
        $urls[] = 'https://www.gstatic.com';
    }
    return $urls;
}

/**
 * Gravity Forms ships about 38KB of render-blocking CSS in four files. Only
 * Contact shows its form in the first screen; everywhere else the form sits at
 * the foot of the page, so its styles load without holding the first paint
 * (print media, swapped to all on load, with a noscript fallback). Order in
 * the document is unchanged, so the theme's overrides still win.
 */
add_filter( 'style_loader_tag', 'vc_defer_form_styles', 10, 2 );
function vc_defer_form_styles( $tag, $handle ) {
	if ( is_admin() || ( 0 !== strpos( $handle, 'gravity_forms_' ) && 0 !== strpos( $handle, 'gform_' ) ) ) {
		return $tag;
	}
	if ( is_page_template( 'page-templates/page-contact-us.php' ) ) {
		return $tag;
	}
	$deferred = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $tag );
	if ( $deferred === $tag ) {
		return $tag;
	}
	return $deferred . '<noscript>' . trim( preg_replace( "/\sid='[^']*'/", '', $tag, 1 ) ) . "</noscript>\n";
}
