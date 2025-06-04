<?php

add_action( 'wp_enqueue_scripts', 'add_custom_scripts' );
function add_custom_scripts() {
    wp_deregister_script( 'jquery-ui' );

    wp_enqueue_style( 'site', VC_TEMPLATE_URI . mix('/css/app.css'), [], null, 'all' );

    if ( is_front_page() ) {
        wp_enqueue_script( 'homepage', VC_TEMPLATE_URI . mix('/js/homepage.js'), [ 'jquery' ], null, true );
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
