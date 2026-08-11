<?php

add_action( 'wp_enqueue_scripts', 'add_custom_scripts' );
function add_custom_scripts() {
    wp_deregister_script( 'jquery-ui' );

    wp_enqueue_style( 'site', VC_TEMPLATE_URI . mix('/css/app.css'), [], null, 'all' );

    if ( is_front_page() ) {
        wp_enqueue_script( 'homepage', VC_TEMPLATE_URI . mix('/js/homepage.js'), [ 'jquery' ], null, true );
        // Tell webpack where to load lazily-imported chunks from (the theme's dist dir),
        // otherwise dynamic imports resolve against the site root on WordPress.
        wp_add_inline_script( 'homepage', 'window.__vc_public_path=' . wp_json_encode( VC_TEMPLATE_URI . '/dist/' ) . ';', 'before' );
    }

    // is_singular('post'), not is_single(): the latter is true for CPT
    // singles too and would leak the blog bundle onto project pages.
    if ( is_singular( 'post' ) ) {
        wp_enqueue_script( 'single-blog', VC_TEMPLATE_URI . mix('/js/single-blog.js'), [ 'jquery' ], null, true );
    }

    if ( is_home() || is_category() || is_search() ) {
        wp_enqueue_script( 'archive-blog', VC_TEMPLATE_URI . mix('/js/archive-blog.js'), [ 'jquery' ], null, true );
        wp_localize_script( 'archive-blog', 'vcInsights', [
            'rest'    => esc_url_raw( rest_url( 'vc/v1/insights' ) ),
            'blogUrl' => get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ),
        ] );
    }

    if ( is_author() ) {
        wp_enqueue_script( 'archive-author', VC_TEMPLATE_URI . mix('/js/archive-author.js'), [ 'jquery' ], null, true );
    }

    if ( is_page_template( 'page-templates/page-free-website.php' ) ) {
        wp_enqueue_script( 'free-website', VC_TEMPLATE_URI . mix('/js/free-website.js'), [ 'jquery' ], null, true );
        // Form 7 carries the `enquiry-form` cssClass, so it gets the same
        // entrance, dataLayer events and confirmation focus handling the
        // landing template already loads (parity fix, Aug 2026).
        wp_enqueue_script( 'enquiry-form', VC_TEMPLATE_URI . mix('/js/enquiry-form.js'), [ 'jquery' ], null, true );
    }

    if ( is_page_template( 'page-templates/page-landing-page.php' ) ) {
        wp_enqueue_script( 'landing', VC_TEMPLATE_URI . mix('/js/landing.js'), [ 'jquery' ], null, true );
        // A landing CTA block can embed the multi-step enquiry form (id 5); its
        // bundle/SCSS key on the `enquiry-form` cssClass, so this covers either
        // the multi-step or a single-step form with no extra wiring.
        wp_enqueue_script( 'enquiry-form', VC_TEMPLATE_URI . mix('/js/enquiry-form.js'), [ 'jquery' ], null, true );
    }

    if ( is_page_template( 'page-templates/page-contact-us.php' ) ) {
        wp_enqueue_script( 'contact', VC_TEMPLATE_URI . mix('/js/contact.js'), [ 'jquery' ], null, true );
        // Multi-step enquiry form (form id 5). When the form moves to another
        // template, extend this condition — the bundle and SCSS are otherwise
        // self-contained (both key on the form's `enquiry-form` cssClass).
        wp_enqueue_script( 'enquiry-form', VC_TEMPLATE_URI . mix('/js/enquiry-form.js'), [ 'jquery' ], null, true );
    }

    if ( is_page_template( 'page-templates/page-about-us.php' ) ) {
        wp_enqueue_script( 'about', VC_TEMPLATE_URI . mix('/js/about.js'), [ 'jquery' ], null, true );
    }

    if ( is_page_template( 'page-templates/page-services-hub.php' ) ) {
        wp_enqueue_script( 'services-hub', VC_TEMPLATE_URI . mix('/js/services-hub.js'), [ 'jquery' ], null, true );
    }

    if ( is_tax( 'service' ) ) {
        wp_enqueue_script( 'service', VC_TEMPLATE_URI . mix('/js/service.js'), [ 'jquery' ], null, true );
    }

    if ( is_post_type_archive( 'project' ) || is_singular( 'project' ) ) {
        wp_enqueue_script( 'project', VC_TEMPLATE_URI . mix('/js/project.js'), [ 'jquery' ], null, true );
    }

    if ( is_post_type_archive( 'case_study' ) || is_singular( 'case_study' ) ) {
        wp_enqueue_script( 'case-study', VC_TEMPLATE_URI . mix('/js/case-study.js'), [ 'jquery' ], null, true );
    }

    wp_enqueue_script( 'global', VC_TEMPLATE_URI . mix('/js/global.js'), [ 'jquery' ], null, true );

    wp_enqueue_script( 'header', VC_TEMPLATE_URI . mix('/js/header.js'), [ 'jquery' ], null, true );

    wp_enqueue_script( 'footer', VC_TEMPLATE_URI . mix('/js/footer.js'), [ 'jquery' ], null, true );
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
