<?php
/**
 * Insights filter REST endpoint.
 *
 * Powers the AJAX category/search filter on the blog index (assets/js/blog/
 * filter.js). Public read-only GET: returns the rendered post cards plus
 * pagination metadata for a given category, search term and page. The same
 * URLs work without JS (category archives, the search page), so this is pure
 * progressive enhancement.
 */

add_action( 'rest_api_init', function () {
	register_rest_route( 'vc/v1', '/insights', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => 'vc_insights_filter_endpoint',
		'args'                => [
			'category' => [ 'sanitize_callback' => 'absint' ],
			'search'   => [ 'sanitize_callback' => 'sanitize_text_field' ],
			'paged'    => [ 'sanitize_callback' => 'absint' ],
		],
	] );
} );

function vc_insights_filter_endpoint( WP_REST_Request $request ) {
	$category = (int) $request->get_param( 'category' );
	$search   = (string) $request->get_param( 'search' );
	$paged    = max( 1, (int) $request->get_param( 'paged' ) );

	$args = [
		'post_type'           => 'post',
		'posts_per_page'      => 12,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
	];

	if ( $category ) {
		$args['cat'] = $category;
	}

	if ( '' !== $search ) {
		$args['s'] = $search;
	}

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content', 'card' );
		}
	} else {
		echo '<p class="insights-empty">No insights match that filter. Try another category or search.</p>';
	}

	$cards = ob_get_clean();
	wp_reset_postdata();

	return new WP_REST_Response( [
		'cards'    => $cards,
		'found'    => (int) $query->found_posts,
		'maxPages' => (int) $query->max_num_pages,
		'paged'    => $paged,
	], 200 );
}
