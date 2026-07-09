<?php

// The "What We Do" mega menu. The header main menu keeps a single "What We Do"
// item; its dropdown is rendered dynamically from the service taxonomy — the
// pillars in the Global Settings > Service List order (menu-ticked), each with
// its child services alphabetically — rather than hand-placed child menu items,
// so the menu maintains itself. Built as nav-menu filters that reuse the
// existing dropdown contract (.menu-item-has-children > .sub-menu) so the header
// CSS and dropdown.js keep working; the panel carries an extra .sub-menu--mega
// class for its wide multi-column layout, and the <li> a .menu-item-mega class.

// The "What We Do" item is the one whose URL resolves to the services hub, so
// the match survives a label change and works whether the URL is stored
// absolute or relative.
function vc_is_services_menu_item( $item ) {
	if ( empty( $item->url ) ) {
		return false;
	}
	$path = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
	return 'services' === $path;
}

// True only for the header render of the main menu, never the footer "Explore"
// render (which strips the services item via the vc_footer_explore filter).
function vc_is_header_main_menu( $args ) {
	$location = is_object( $args ) ? ( $args->theme_location ?? '' ) : '';
	return 'main-menu' === $location && empty( $GLOBALS['vc_footer_explore'] );
}

// Drop the hand-placed child items of the services parent so only the dynamic
// panel renders. The parent keeps its menu-item-has-children class (set by
// _wp_menu_item_classes_by_context before this filter runs), so the caret and
// the CSS dropdown still fire.
add_filter( 'wp_nav_menu_objects', 'vc_mega_strip_static_children', 10, 2 );
function vc_mega_strip_static_children( $items, $args ) {
	if ( ! vc_is_header_main_menu( $args ) ) {
		return $items;
	}
	$services_id = 0;
	foreach ( $items as $item ) {
		if ( vc_is_services_menu_item( $item ) ) {
			$services_id = (int) $item->ID;
			break;
		}
	}
	if ( ! $services_id ) {
		return $items;
	}
	return array_values( array_filter( $items, function ( $item ) use ( $services_id ) {
		return (int) $item->menu_item_parent !== $services_id;
	} ) );
}

// Tag the services <li> so the wide panel can be positioned and styled apart
// from ordinary dropdowns.
add_filter( 'nav_menu_css_class', 'vc_mega_service_item_class', 10, 3 );
function vc_mega_service_item_class( $classes, $item, $args ) {
	if ( vc_is_header_main_menu( $args ) && vc_is_services_menu_item( $item ) ) {
		$classes[] = 'menu-item-mega';
	}
	return $classes;
}

// Append the dynamic mega panel after the services link (both the desktop and
// mobile main-menu renders use this same markup; CSS makes one a wide panel and
// the other a two-level accordion).
add_filter( 'walker_nav_menu_start_el', 'vc_mega_render_panel', 10, 4 );
function vc_mega_render_panel( $item_output, $item, $depth, $args ) {
	if ( ! vc_is_header_main_menu( $args ) || ! vc_is_services_menu_item( $item ) ) {
		return $item_output;
	}

	$pillars = vc_ordered_services( 'menu' );
	if ( ! $pillars ) {
		return $item_output;
	}

	$panel = '<ul class="sub-menu sub-menu--mega">';
	foreach ( $pillars as $pillar ) {
		$p_link = get_term_link( $pillar );
		if ( is_wp_error( $p_link ) ) {
			continue;
		}
		$panel .= '<li class="mega-col">';
		$panel .= '<a class="mega-col-head" href="' . esc_url( $p_link ) . '">' . esc_html( $pillar->name ) . '</a>';

		$kids = vc_service_children( $pillar->term_id );
		if ( $kids ) {
			$panel .= '<ul class="mega-col-list">';
			foreach ( $kids as $kid ) {
				$k_link = get_term_link( $kid );
				if ( is_wp_error( $k_link ) ) {
					continue;
				}
				$panel .= '<li><a href="' . esc_url( $k_link ) . '">' . esc_html( $kid->name ) . '</a></li>';
			}
			$panel .= '</ul>';
		}
		$panel .= '</li>';
	}
	$panel .= '</ul>';

	return $item_output . $panel;
}
