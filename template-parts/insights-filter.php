<?php
/**
 * Insights filter bar: article search (left) + category dropdown (right),
 * above the card grid on the index and category archives.
 *
 * Progressive enhancement: the search is a real form to the search page and
 * the category links are real links to category archives, so it works with no
 * JS. With JS (assets/js/blog/filter.js) the search and category picks are
 * intercepted and the grid is filtered in place via the vc/v1/insights REST
 * endpoint.
 */

$filter_terms = get_categories( [ 'hide_empty' => true ] );

if ( empty( $filter_terms ) || is_wp_error( $filter_terms ) ) {
	return;
}

$filter_current_id = is_category() ? (int) get_queried_object_id() : 0;
$filter_search     = is_search() ? get_search_query() : '';
$filter_blog       = get_permalink( (int) get_option( 'page_for_posts' ) );

if ( ! $filter_blog ) {
	$filter_blog = home_url( '/blog/' );
}

$filter_label = 'All categories';
if ( $filter_current_id ) {
	$filter_current_term = get_term( $filter_current_id );
	if ( $filter_current_term && ! is_wp_error( $filter_current_term ) ) {
		$filter_label = $filter_current_term->name;
	}
}
?>

<div class="insights-filter" data-insights-filter>
	<form class="insights-filter-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<svg class="insights-filter-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
			<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
			<path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
		</svg>
		<input type="search" name="s" class="insights-filter-input" placeholder="Search articles…" aria-label="Search articles" value="<?php echo esc_attr( $filter_search ); ?>" autocomplete="off">
		<button type="button" class="insights-filter-clear" aria-label="Clear search"<?php echo $filter_search ? '' : ' hidden'; ?>>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
				<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
			</svg>
		</button>
		<button type="submit" class="insights-filter-submit">Search</button>
	</form>

	<div class="insights-filter-dropdown" data-insights-dropdown>
		<button type="button" class="insights-filter-toggle" aria-expanded="false" aria-haspopup="true" aria-controls="insights-cat-panel">
			<span class="insights-filter-toggle-label">Category:</span>
			<span class="insights-filter-current"><?php echo esc_html( $filter_label ); ?></span>
			<svg class="insights-filter-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
				<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>

		<div class="insights-filter-panel" id="insights-cat-panel" hidden>
			<div class="insights-filter-panel-search">
				<input type="text" class="insights-filter-cat-search" placeholder="Find a category…" aria-label="Find a category" autocomplete="off">
			</div>
			<ul class="insights-filter-list">
				<li class="insights-filter-item">
					<a class="insights-filter-link<?php echo ( ! $filter_current_id && '' === $filter_search ) ? ' is-active' : ''; ?>"
					   href="<?php echo esc_url( $filter_blog ); ?>"
					   data-category="0"
					   <?php echo ( ! $filter_current_id && '' === $filter_search ) ? 'aria-current="page"' : ''; ?>>All categories</a>
				</li>
				<?php foreach ( $filter_terms as $filter_term ) :
					$filter_link   = get_term_link( $filter_term );
					$filter_active = ( $filter_current_id === (int) $filter_term->term_id );

					if ( is_wp_error( $filter_link ) ) {
						continue;
					}
					?>
					<li class="insights-filter-item">
						<a class="insights-filter-link<?php echo $filter_active ? ' is-active' : ''; ?>"
						   href="<?php echo esc_url( $filter_link ); ?>"
						   data-category="<?php echo (int) $filter_term->term_id; ?>"
						   <?php echo $filter_active ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $filter_term->name ); ?></a>
					</li>
				<?php endforeach; ?>
				<li class="insights-filter-empty" hidden>No matching categories.</li>
			</ul>
		</div>
	</div>

	<p class="insights-filter-status visually-hidden" aria-live="polite" role="status"></p>
</div>
