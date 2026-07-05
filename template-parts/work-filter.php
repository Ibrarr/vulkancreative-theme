<?php
/**
 * Work archive filter bar: one chip per service that has published projects,
 * after an "All Work" chip. Progressive enhancement, the insights-filter
 * recipe: every chip is a real link (/work/ or /work/?service={slug}) handled
 * server-side, and assets/js/project/filter.js intercepts them to filter the
 * grid in place. On small screens the chip row collapses behind a dropdown
 * toggle (the chips stack as 44px rows inside a panel); without JS the
 * toggle hides and the chips simply stay visible. The visually-hidden status
 * line announces the result count for screen readers.
 *
 * $args:
 *  - 'terms'      WP_Term[] to render, already ordered. Required.
 *  - 'active'     The active service slug ('' = all).
 *  - 'base'       Archive URL the chips link to. Defaults to /work/, so the
 *                 case-studies archive passes its own.
 *  - 'all_label'  The first chip's label. Defaults to the Work Page option.
 *  - 'aria_label' The nav's accessible label. Defaults to the projects one.
 */

$filter_terms  = $args['terms'] ?? [];
$filter_active = $args['active'] ?? '';

if ( empty( $filter_terms ) ) {
	return;
}

$filter_all_label = $args['all_label'] ?? ( get_field( 'wk_filter_all_label', 'options' ) ?: 'All Work' );
$filter_base      = $args['base'] ?? get_post_type_archive_link( 'project' );
$filter_aria      = $args['aria_label'] ?? 'Filter projects by service';

$filter_current = $filter_all_label;
foreach ( $filter_terms as $filter_term ) {
	if ( $filter_active === $filter_term->slug ) {
		$filter_current = $filter_term->name;
		break;
	}
}
?>
<nav class="work-filter" aria-label="<?php echo esc_attr( $filter_aria ); ?>" data-work-filter>
	<button type="button" class="work-filter-toggle" aria-expanded="false" aria-controls="work-filter-panel" data-work-toggle>
		<span class="work-filter-toggle-label">Service:</span>
		<span class="work-filter-current" data-work-current><?php echo esc_html( $filter_current ); ?></span>
		<svg class="work-filter-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
	</button>
	<div class="work-filter-panel" id="work-filter-panel" data-work-panel>
		<a class="work-chip<?php echo '' === $filter_active ? ' is-active' : ''; ?>"
		   href="<?php echo esc_url( $filter_base ); ?>"
		   data-service=""
		   <?php echo '' === $filter_active ? 'aria-current="true"' : ''; ?>><?php echo esc_html( $filter_all_label ); ?></a>
		<?php foreach ( $filter_terms as $filter_term ) :
			$filter_is_active = $filter_active === $filter_term->slug;
			?>
			<a class="work-chip<?php echo $filter_is_active ? ' is-active' : ''; ?>"
			   href="<?php echo esc_url( add_query_arg( 'service', $filter_term->slug, $filter_base ) ); ?>"
			   data-service="<?php echo esc_attr( $filter_term->slug ); ?>"
			   <?php echo $filter_is_active ? 'aria-current="true"' : ''; ?>><?php echo esc_html( $filter_term->name ); ?></a>
		<?php endforeach; ?>
	</div>
</nav>
<p class="work-filter-status visually-hidden" aria-live="polite" role="status" data-work-status></p>
