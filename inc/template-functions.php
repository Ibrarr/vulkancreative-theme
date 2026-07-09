<?php

/**
 * Add schema to pages
 *
 * @return void
 */
function vc_schema_type() {
	$schema = 'https://schema.org/';
	// CPT branches must precede is_single(), which is true for CPT
	// singles too and would mislabel them as Article.
	if ( is_singular( 'project' ) || is_singular( 'case_study' ) ) {
		$type = 'WebPage';
	} elseif ( is_post_type_archive( 'project' ) || is_post_type_archive( 'case_study' ) ) {
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
 * When every part is blank, $fallback — an already-highlighted HTML string —
 * is returned instead. Shared by the field and sub-field readers below.
 */
function vc_compose_heading_parts( $start, $red, $end, $fallback = '' ) {
	$start = trim( (string) $start );
	$red   = trim( (string) $red );
	$end   = trim( (string) $end );

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

/**
 * Compose a heading from the {base}_start / _red / _end ACF fields on a post,
 * option or taxonomy context.
 */
function vc_heading_parts( $base, $acf_id = false, $fallback = '' ) {
	return vc_compose_heading_parts(
		get_field( $base . '_start', $acf_id ),
		get_field( $base . '_red', $acf_id ),
		get_field( $base . '_end', $acf_id ),
		$fallback
	);
}

/**
 * As vc_heading_parts() but reads sub-fields — for a heading composed from
 * {base}_start / _red / _end inside a have_rows() / the_row() loop (repeater or
 * flexible-content row). Must be called inside the row context.
 */
function vc_heading_parts_sub( $base, $fallback = '' ) {
	return vc_compose_heading_parts(
		get_sub_field( $base . '_start' ),
		get_sub_field( $base . '_red' ),
		get_sub_field( $base . '_end' ),
		$fallback
	);
}

/**
 * Templates that use the slim header variant (logo + theme toggle + one CTA,
 * no nav, no hamburger) and the mobile sticky CTA: the free-website offer page
 * and the reusable Landing Page template. Keep this list as the single source
 * for every slim-header conditional across header.php / footer.php / enqueues.
 */
function vc_is_slim_templates() {
	return array(
		'page-templates/page-free-website.php',
		'page-templates/page-landing-page.php',
	);
}

function vc_is_slim_header() {
	foreach ( vc_is_slim_templates() as $tpl ) {
		if ( is_page_template( $tpl ) ) {
			return true;
		}
	}
	return false;
}

/**
 * The slim header's single CTA as [ label, href ], per slim template. The
 * landing template exposes editable label + target; the free-website page
 * reads its own label and links to its enquire anchor.
 */
function vc_slim_header_cta() {
	if ( is_page_template( 'page-templates/page-landing-page.php' ) ) {
		$label = get_field( 'lp_header_cta_label' );
		$href  = get_field( 'lp_header_cta_target' );
		return array( $label ?: 'Get in Touch', $href ?: '#lp-cta' );
	}

	$label = get_field( 'fw_header_cta_label' );
	return array( $label ?: 'Get Your Free Website', '#enquire' );
}

/**
 * The ordered parent services for a placement, driven by the Global Settings
 * "Service List" (inc/service-list-fields.php). $location is one of 'menu',
 * 'footer', 'homepage', 'hub' — rows with that placement toggle off are
 * skipped — or null for the full ordered list (used by the archive filters).
 * Falls back to all top-level service terms by name until the list is set up.
 *
 * @return WP_Term[]
 */
function vc_ordered_services( $location = null ) {
	$toggle = $location ? 'show_' . $location : '';
	$terms  = array();

	if ( function_exists( 'have_rows' ) && have_rows( 'service_list', 'options' ) ) {
		while ( have_rows( 'service_list', 'options' ) ) {
			the_row();
			$term_id = (int) get_sub_field( 'service' );
			if ( ! $term_id ) {
				continue;
			}
			if ( $toggle && ! get_sub_field( $toggle ) ) {
				continue;
			}
			$term = get_term( $term_id, 'service' );
			if ( $term && ! is_wp_error( $term ) ) {
				$terms[] = $term;
			}
		}
	}

	if ( $terms ) {
		return $terms;
	}

	// Fallback until the Service List is configured: top-level terms by name.
	$fallback = get_terms( array(
		'taxonomy'   => 'service',
		'hide_empty' => false,
		'parent'     => 0,
		'orderby'    => 'name',
	) );
	return is_wp_error( $fallback ) ? array() : $fallback;
}

/**
 * A parent service's child services, alphabetically. Used on pillar pages and
 * in the mega menu. Returns an empty array for a leaf term (no children).
 *
 * @return WP_Term[]
 */
function vc_service_children( $parent_id ) {
	$children = get_terms( array(
		'taxonomy'   => 'service',
		'hide_empty' => false,
		'parent'     => (int) $parent_id,
		'orderby'    => 'name',
	) );
	return is_wp_error( $children ) ? array() : $children;
}