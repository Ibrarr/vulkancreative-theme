<?php

/**
 * Modify the document title separator.
 */
add_filter( 'document_title_separator', 'vc_document_title_separator' );
function vc_document_title_separator( $sep ) {
	$sep = esc_html( '|' );

	return $sep;
}

/**
 * Modify the title before displaying it.
 */
add_filter( 'the_title', 'vc_title' );
function vc_title( $title ) {
	if ( $title == '' ) {
		return esc_html( '...' );
	} else {
		return wp_kses_post( $title );
	}
}

/**
 * Add schema to menu link
 */
add_filter( 'nav_menu_link_attributes', 'vc_schema_url', 10 );
function vc_schema_url( $atts ) {
	$atts['itemprop'] = 'url';

	return $atts;
}

/**
 * Disable big image size threshold.
 */
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Override intermediate image sizes.
 */
add_filter( 'intermediate_image_sizes_advanced', 'vc_image_insert_override' );
function vc_image_insert_override( $sizes ) {
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );

	return $sizes;
}

/**
 * Enable classic editor
 */
add_filter( 'use_block_editor_for_post', '__return_false', 10 );

add_filter( 'post_type_link', 'modify_partner_post_link', 10, 2 );
function modify_partner_post_link( $url, $post ) {
    if ( $post->post_type == 'partner_content' ) {
        $news_link = get_post_meta( $post->ID, 'link_to_content', true );
        if ( $news_link ) {
            return $news_link;
        }
    }

    return $url;
}

/**
 * Add body classes
 */
add_filter( 'body_class', 'custom_body_classes' );
function custom_body_classes( $classes ) {
    // Dark-first: dark mode is the default for every page. An inline script
    // straight after the opening body tag removes the class before first paint
    // for visitors who chose light mode (see header.php).
    $classes[] = 'dark-mode';

    if ( is_tax( 'practice_area' ) ) {
        $term = get_queried_object();
        if ( $term ) {
            if ( $term->parent == 0 ) {
                $classes[] = 'practice-area-parent';
            } else {
                $classes[] = 'practice-area-child';
            }
        }
    }

	return $classes;
}

/**
 * Fetch all Gravity Forms for ACF dropdown field
 */
//add_filter( 'acf/load_field/name=enquire_form', 'acf_populate_gf_forms_ids' );
//function acf_populate_gf_forms_ids( $field ) {
//	if ( class_exists( 'GFFormsModel' ) ) {
//		$choices = [];
//
//		foreach ( \GFFormsModel::get_forms() as $form ) {
//			$choices[ $form->id ] = $form->title;
//		}
//
//		$field['choices'] = $choices;
//	}
//
//	return $field;
//}

add_filter(
    'wpseo_breadcrumb_single_link',
    function ( $link_output ) {
        if ( strpos( $link_output, 'breadcrumb_last' ) !== false ) {
            $link_output = '';
        }
        return $link_output;
    }
);

// Indexing policy (categories indexed as topic hubs; author/date/search out of
// the index) is governed by Yoast's own Search Appearance settings, so robots
// meta, the XML sitemap and canonicals stay consistent — see the wpseo_titles
// options noindex-tax-category=false and noindex-author-wpseo=true. A theme
// robots filter is deliberately NOT used: it would flip the meta tag but leave
// the sitemap out of sync.
// Enrich Yoast's Organization node (@id /#organization) with the company
// contact details from Global Settings and the social profiles the footer
// links to, so the knowledge graph matches the visible site. Yoast stays the
// single source of the node itself.
add_filter(
	'wpseo_schema_organization',
	function ( $data ) {
		$email = get_field( 'company_email', 'options' );
		$phone = get_field( 'company_phone', 'options' );
		$location = get_field( 'company_location', 'options' );

		if ( $email ) {
			$data['email'] = $email;
		}
		if ( $phone ) {
			$data['telephone'] = $phone;
		}
		if ( $location ) {
			$data['address'] = [
				'@type'         => 'PostalAddress',
				'streetAddress' => $location,
				'addressLocality' => 'London',
				'addressCountry'  => 'GB',
			];
		}
		$data['sameAs'] = array_values( array_unique( array_merge(
			isset( $data['sameAs'] ) ? (array) $data['sameAs'] : [],
			[
				'https://www.linkedin.com/company/vulkan-creative/',
				'https://www.tiktok.com/@vulkancreative',
				'https://www.instagram.com/vulkancreative/',
				'https://www.youtube.com/@VulkanCreative',
			]
		) ) );

		return $data;
	}
);

// One menu serves every page. Section-anchor items are stored as absolute
// URLs ("/#why") so they navigate correctly from inner pages; on the front
// page itself they flatten to in-page anchors ("#why") so the smooth-scroll
// interception and the scrollspy keep working exactly as before.
add_filter(
	'nav_menu_link_attributes',
	function ( $atts ) {
		if ( ! is_front_page() || empty( $atts['href'] ) ) {
			return $atts;
		}
		$parts     = wp_parse_url( $atts['href'] );
		$path      = isset( $parts['path'] ) ? untrailingslashit( $parts['path'] ) : '';
		$home_path = untrailingslashit( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );
		$host      = isset( $parts['host'] ) ? $parts['host'] : '';
		$home_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( ( $host !== '' && $host !== $home_host ) || ( $path !== '' && $path !== $home_path ) ) {
			return $atts;
		}
		// A fragment flattens to its in-page anchor; the bare homepage link
		// (the Home item) becomes the #top anchor.
		$atts['href'] = '#' . ( ! empty( $parts['fragment'] ) ? $parts['fragment'] : 'top' );
		return $atts;
	}
);
