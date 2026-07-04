<?php

/**
 * Add schema to pages
 *
 * @return void
 */
function vc_schema_type() {
	$schema = 'https://schema.org/';
	// Project branches must precede is_single(), which is true for CPT
	// singles too and would mislabel them as Article.
	if ( is_singular( 'project' ) ) {
		$type = 'WebPage';
	} elseif ( is_post_type_archive( 'project' ) ) {
		$type = 'CollectionPage';
	} elseif ( is_single() ) {
		$type = "Article";
	} elseif ( is_author() ) {
		$type = 'ProfilePage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} elseif ( is_page_template( 'page-templates/page-about-us.php' ) ) {
		$type = 'AboutPage';
	} elseif ( is_page_template( 'page-templates/page-services-hub.php' ) ) {
		$type = 'CollectionPage';
	} else {
		$type = 'WebPage';
	}
	echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}

add_action( 'init', 'register_custom_page_templates' );
function register_custom_page_templates() {
	$template_dir = VC_TEMPLATE_DIR . '/page-templates/';

	$template_files = glob( $template_dir . '*.php' );

	foreach ( $template_files as $template_file ) {
		$template_name  = str_replace( array( $template_dir, '.php' ), '', $template_file );
		$template_label = ucwords( str_replace( '-', ' ', $template_name ) );
		$template_label = str_replace( '_', ' ', $template_label );

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-page-template', $template_name, array(
			'label' => $template_label,
		) );
	}
}

/**
 * Compose a section heading from its three editable parts (start, red, end).
 * The red part renders as the standard <span> highlight. Joining is
 * punctuation-aware, so an end part of '.' or '?' attaches without a space.
 * When every part is blank (or the fields do not exist), $fallback — an
 * already-highlighted HTML string — is returned instead.
 */
function vc_heading_parts( $base, $acf_id = false, $fallback = '' ) {
	$start = trim( (string) get_field( $base . '_start', $acf_id ) );
	$red   = trim( (string) get_field( $base . '_red', $acf_id ) );
	$end   = trim( (string) get_field( $base . '_end', $acf_id ) );

	if ( '' === $start && '' === $red && '' === $end ) {
		return $fallback;
	}

	$join = function ( $a, $b ) {
		if ( '' === $a ) {
			return $b;
		}
		if ( '' === $b ) {
			return $a;
		}
		$space = preg_match( '/^[.,!?:;]/', wp_strip_all_tags( $b ) ) ? '' : ' ';
		return $a . $space . $b;
	};

	$out = esc_html( $start );
	if ( '' !== $red ) {
		$out = $join( $out, '<span>' . esc_html( $red ) . '</span>' );
	}
	if ( '' !== $end ) {
		$out = $join( $out, esc_html( $end ) );
	}
	return $out;
}