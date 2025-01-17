<?php

add_action( 'wp_enqueue_scripts', 'add_custom_scripts' );
function add_custom_scripts() {
    wp_deregister_script( 'jquery-ui' );

    wp_enqueue_style( 'site', VC_TEMPLATE_URI . mix('/dist/css/app.css'), [], null, 'all' );

    if ( is_front_page() ) {
        wp_enqueue_script( 'homepage', VC_TEMPLATE_URI . mix('/dist/js/homepage.js'), [ 'jquery' ], null, true );
    }

    wp_enqueue_script( 'global', VC_TEMPLATE_URI . mix('/dist/js/global.js'), [ 'jquery' ], null, true );

    wp_enqueue_script( 'header', VC_TEMPLATE_URI . mix('/dist/js/header.js'), [ 'jquery' ], null, true );
}

function mix($path) {
    $manifestPath = VC_TEMPLATE_DIR . '/dist/mix-manifest.json';
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        return $manifest[$path] ?? $path;
    }
    return $path;
}