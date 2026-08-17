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

// Strip the current-page crumb only on content singles, where the visible H1
// already repeats the title (blog posts, project and case-study showcases). On
// pages (Contact, legal) and archives (category, work, case-studies) the
// terminal crumb is the wayfinding, so leaving it in place keeps the trail
// meaningful and avoids a lone "Home" crumb reading as an eyebrow tag above the
// heading (and keeps the visible trail in step with the BreadcrumbList schema).
add_filter(
    'wpseo_breadcrumb_single_link',
    function ( $link_output ) {
        if ( is_singular() && ! is_page() && strpos( $link_output, 'breadcrumb_last' ) !== false ) {
            $link_output = '';
        }
        return $link_output;
    }
);

// Yoast builds its own titles and ignores the document_title_separator filter
// above, so it emits a hyphen on generated pages while the homepage's saved
// title uses the house pipe. Normalise the separator that sits before the brand
// name to "|", and guarantee the brand suffix on any title that omits it (some
// posts), so every SERP result carries "… | Vulkan Creative" consistently.
function vc_normalise_brand_title( $title ) {
	$brand = get_bloginfo( 'name' );
	if ( '' === $title || '' === $brand ) {
		return $title;
	}
	$title = preg_replace(
		'/\s[-\x{2013}\x{2014}]\s(' . preg_quote( $brand, '/' ) . ')\s*$/u',
		' | $1',
		$title
	);
	if ( false === strpos( $title, $brand ) ) {
		$title .= ' | ' . $brand;
	}
	return $title;
}
add_filter( 'wpseo_title', 'vc_normalise_brand_title', 20 );

// og:title goes out through a different Yoast pipe and missed the fix above
// (46 pages carried the dash separator, posts had no brand at all), so social
// previews now run the same normalisation as the SERP title.
add_filter( 'wpseo_opengraph_title', 'vc_normalise_brand_title', 20 );

// The 404-preview page (page-templates/page-404-preview.php) exists only so the
// team can view the 404 design at a stable, crawlable URL; the real 404.php
// already returns a proper 404 status. Keep the preview out of the index so
// Google never treats a "Page not found" screen as a live landing page.
add_filter(
	'wpseo_robots_array',
	function ( $robots ) {
		if ( is_page_template( 'page-templates/page-404-preview.php' ) ) {
			$robots['index'] = 'noindex';
		}
		return $robots;
	}
);

// Service term pages sit under the Services hub page in the site hierarchy,
// so inject the hub crumb after Home; Yoast only knows the term itself.
add_filter(
	'wpseo_breadcrumb_links',
	function ( $links ) {
		if ( ! is_tax( 'service' ) || ! is_array( $links ) ) {
			return $links;
		}
		$hub = get_page_by_path( 'services' );
		array_splice( $links, 1, 0, [
			[
				'text' => $hub ? get_the_title( $hub ) : 'Services',
				'url'  => $hub ? get_permalink( $hub ) : home_url( '/services/' ),
			],
		] );
		return $links;
	}
);

// The work and case-studies archives' service filter chips link to
// /work/?service={slug} and /case-studies/?service={slug}. But 'service' is
// also the service taxonomy's own query var, so left alone it would flag the
// main query as is_tax('service') and drag in every service term-page
// behaviour (the 3-post query cap, the service bundle, the tax-service body
// class, the footer CTA hide, the breadcrumb splice). Move it into a private
// query var before the query flags are parsed; both archive templates read
// vc_work_service to mark the server-rendered filter state, and Yoast
// canonicalises the filtered URLs back to the bare archives.
add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vc_work_service';
		return $vars;
	}
);
add_filter( 'request', 'vc_work_service_request' );
function vc_work_service_request( $qv ) {
	if ( isset( $qv['post_type'], $qv['service'] ) && in_array( $qv['post_type'], array( 'project', 'case_study' ), true ) ) {
		$slug = sanitize_title( (string) $qv['service'] );
		// Old bookmarks with a retired pillar slug still preselect the live chip.
		$aliases              = function_exists( 'vc_service_slug_aliases' ) ? vc_service_slug_aliases() : array();
		$qv['vc_work_service'] = isset( $aliases[ $slug ] ) ? $aliases[ $slug ] : $slug;
		unset( $qv['service'] );
	}
	return $qv;
}

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

// The footer "Explore" column renders the main menu but should read as the
// header nav MINUS the services entry ("What We Do", which has its own footer
// Services column) and the CTA button (not navigation). A flag set around the
// footer render (see footer.php) scopes this, so the header menu is untouched.
add_filter(
	'wp_nav_menu_objects',
	function ( $items, $args ) {
		if ( empty( $GLOBALS['vc_footer_explore'] ) || ! is_array( $items ) ) {
			return $items;
		}
		return array_values( array_filter( $items, function ( $item ) {
			if ( (int) $item->menu_item_parent !== 0 ) {
				return false; // drop the sub-items (the service links)
			}
			$classes = (array) $item->classes;
			if ( in_array( 'menu-item-has-children', $classes, true ) ) {
				return false; // drop the "What We Do" services entry
			}
			if ( in_array( 'menu-button', $classes, true ) ) {
				return false; // drop the "Let's Talk" CTA button
			}
			return true;
		} ) );
	},
	10,
	2
);

// Parent menu items with a sub-menu ("What We Do") carry a visible caret so
// the dropdown is discoverable. Rendered server-side for both main-menu
// renders; the mobile overlay hides it (its injected disclosure button is
// the affordance there) and the desktop bar rotates it while the panel is
// open. aria-hidden: the link text and aria-expanded already say everything.
add_filter(
	'nav_menu_item_title',
	function ( $title, $item, $nav_args, $depth ) {
		if ( 0 !== $depth || 'main-menu' !== ( $nav_args->theme_location ?? '' ) ) {
			return $title;
		}
		if ( ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			return $title;
		}
		return $title . '<span class="menu-caret" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="m6 9 6 6 6-6"/></svg></span>';
	},
	10,
	4
);
