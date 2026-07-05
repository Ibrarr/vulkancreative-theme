<?php
/**
 * The Case Studies archive at /case-studies/ (case_study post type). The hero
 * is the shared page-hero band carrying the section's positioning line; below
 * it one surface-white section carries the service filter chips and the
 * two-up grid of metric-forward cards (uniform columns: on these cards the
 * number is the differentiator, not the size). No visible section h2: the
 * hero h1 carries the message and a visually-hidden h2 keeps the structure
 * (the work-archive precedent). Archive copy lives on the Case Studies Page
 * options page (csp_ fields); pre_get_posts uncaps the query newest-first
 * (vc_case_study_archive_query) and ?service= arrives as the private
 * vc_work_service query var shared with /work/ (inc/filters.php).
 */

get_header();

$cs_hero_heading = vc_heading_parts( 'csp_hero_heading', 'options', 'The <span>results</span> behind the work.' );
$cs_hero_sub     = get_field( 'csp_hero_subheading', 'options' ) ?: 'Each study follows one project from challenge to measured result: what the client needed, what we built, and the numbers it delivered.';

get_template_part( 'template-parts/page', 'hero', [
	'heading'    => $cs_hero_heading,
	'subheading' => $cs_hero_sub,
	'class'      => 'case-studies-hero',
] );

$cs_active = sanitize_title( (string) get_query_var( 'vc_work_service' ) );

// Collect the queried case studies (imageless ones are skipped, matching the
// work grid) and every service term they carry, for the chips.
$cs_cards = [];
$cs_terms = [];
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( ! get_field( 'cs_image' ) ) {
			continue;
		}
		$cs_card_terms = get_the_terms( get_the_ID(), 'service' );
		$cs_card_slugs = [];
		if ( $cs_card_terms && ! is_wp_error( $cs_card_terms ) ) {
			foreach ( $cs_card_terms as $cs_card_term ) {
				$cs_card_slugs[]                      = $cs_card_term->slug;
				$cs_terms[ $cs_card_term->term_id ] = $cs_card_term;
			}
		}
		$cs_cards[] = [
			'id'       => get_the_ID(),
			'services' => $cs_card_slugs,
		];
	}
	wp_reset_postdata();
}

// Chips in the same order as everywhere else: the term's ACF order field.
$cs_terms = array_values( $cs_terms );
usort( $cs_terms, function ( $a, $b ) {
	return (int) get_field( 'order', 'service_' . $a->term_id ) <=> (int) get_field( 'order', 'service_' . $b->term_id );
} );

// A slug with no chip (hand-typed URL) falls back to showing everything.
if ( $cs_active && ! in_array( $cs_active, wp_list_pluck( $cs_terms, 'slug' ), true ) ) {
	$cs_active = '';
}

$cs_visible = 0;
foreach ( $cs_cards as &$cs_card ) {
	$cs_card['matches'] = ! $cs_active || in_array( $cs_active, $cs_card['services'], true );
	if ( $cs_card['matches'] ) {
		$cs_visible++;
	}
}
unset( $cs_card );
?>

<section class="cs-index surface-white">
	<div class="container px-4">
		<?php
		// Structure only: the hero h1 carries the page's message, so the grid
		// gets a hidden heading for screen-reader navigation (the work-archive
		// precedent) rather than a second display heading.
		?>
		<h2 class="visually-hidden">All case studies</h2>

		<?php
		get_template_part( 'template-parts/work-filter', null, [
			'terms'      => $cs_terms,
			'active'     => $cs_active,
			'base'       => get_post_type_archive_link( 'case_study' ),
			'all_label'  => get_field( 'csp_filter_all_label', 'options' ) ?: 'All Case Studies',
			'aria_label' => 'Filter case studies by service',
		] );
		?>

		<?php if ( ! empty( $cs_cards ) ) : ?>
			<div class="row g-4 cs-grid" data-work-grid>
				<?php foreach ( $cs_cards as $cs_card ) :
					get_template_part( 'template-parts/case-study-card', null, [
						'case'   => $cs_card['id'],
						'hidden' => ! $cs_card['matches'],
					] );
				endforeach; ?>
			</div>
			<p class="work-empty" data-work-empty<?php echo $cs_visible ? ' hidden' : ''; ?>>Nothing under that service yet. Choose another, or see all case studies.</p>
		<?php else : ?>
			<p class="work-empty">Case studies are on their way.</p>
		<?php endif; ?>
	</div>
</section>

<?php
// The archive as a CollectionPage carries an ItemList of the case study
// pages, in the same newest-first order as the grid (the /work/ precedent).
if ( ! empty( $cs_cards ) ) {
	$cs_list_items = [];
	foreach ( $cs_cards as $cs_i => $cs_card ) {
		$cs_list_items[] = [
			'@type'    => 'ListItem',
			'position' => $cs_i + 1,
			'name'     => get_field( 'cs_client_name', $cs_card['id'] ) ?: get_the_title( $cs_card['id'] ),
			'url'      => get_permalink( $cs_card['id'] ),
		];
	}
	echo '<script type="application/ld+json">' . wp_json_encode( [
		'@context'        => 'https://schema.org',
		'@type'           => 'ItemList',
		'itemListElement' => $cs_list_items,
	] ) . '</script>';
}

get_footer();
