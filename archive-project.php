<?php
/**
 * The Work archive at /work/ (project post type). The hero is the shared
 * page-hero band; below it one surface-white section carries the service
 * filter chips and the editorial work grid (7/5 then 5/7 rows, computed over
 * the visible subset so the rhythm holds under any filter). No visible
 * section h2: the hero h1 carries the message and a visually-hidden h2 keeps
 * the structure (standing decision, July 2026, work-archive review).
 * Archive copy lives on the Work Page Settings options
 * page (wk_ fields); pre_get_posts uncaps the query newest-first
 * (vc_work_archive_query) and ?service= arrives as the private
 * vc_work_service query var (inc/filters.php).
 */

get_header();

$work_hero_heading = vc_heading_parts( 'wk_hero_heading', 'options', 'The proof is in the <span>work</span>.' );
$work_hero_sub     = get_field( 'wk_hero_subheading', 'options' ) ?: 'A selection of recent brands, websites and campaigns from the forge, across all six of our services.';

get_template_part( 'template-parts/page', 'hero', [
	'heading'    => $work_hero_heading,
	'subheading' => $work_hero_sub,
	'class'      => 'work-hero',
] );

$work_active = sanitize_title( (string) get_query_var( 'vc_work_service' ) );

// Collect the queried projects (imageless ones are skipped, matching the
// wheel) and every service term they carry, for the chips.
$work_cards = [];
$work_terms = [];
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( ! get_field( 'pj_image' ) ) {
			continue;
		}
		$card_terms = get_the_terms( get_the_ID(), 'service' );
		$card_slugs = [];
		if ( $card_terms && ! is_wp_error( $card_terms ) ) {
			foreach ( $card_terms as $card_term ) {
				$card_slugs[]                        = $card_term->slug;
				$work_terms[ $card_term->term_id ] = $card_term;
			}
		}
		$work_cards[] = [
			'id'       => get_the_ID(),
			'services' => $card_slugs,
		];
	}
	wp_reset_postdata();
}

// Chips in the same order as everywhere else: the global Service List order,
// with any term outside that list falling to the end alphabetically.
$work_terms = array_values( $work_terms );
$work_order = wp_list_pluck( vc_ordered_services(), 'term_id' );
usort( $work_terms, function ( $a, $b ) use ( $work_order ) {
	$ai = array_search( $a->term_id, $work_order, true );
	$bi = array_search( $b->term_id, $work_order, true );
	$ai = ( false === $ai ) ? PHP_INT_MAX : $ai;
	$bi = ( false === $bi ) ? PHP_INT_MAX : $bi;
	return $ai === $bi ? strcasecmp( $a->name, $b->name ) : $ai <=> $bi;
} );

// A slug with no chip (hand-typed URL) falls back to showing everything.
if ( $work_active && ! in_array( $work_active, wp_list_pluck( $work_terms, 'slug' ), true ) ) {
	$work_active = '';
}

// The editorial column pattern runs over the VISIBLE subset: per visible
// index, 7/5 then 5/7 rows (filter.js mirrors this exactly).
$work_visible = 0;
foreach ( $work_cards as &$work_card ) {
	$work_card['matches'] = ! $work_active || in_array( $work_active, $work_card['services'], true );
	if ( $work_card['matches'] ) {
		$work_card['pattern'] = ( 0 === $work_visible % 4 || 3 === $work_visible % 4 ) ? 'wide' : 'narrow';
		$work_visible++;
	} else {
		$work_card['pattern'] = 'narrow';
	}
}
unset( $work_card );
?>

<section class="work-index surface-white">
	<div class="container px-4">
		<?php
		// Structure only: the hero h1 carries the page's message, so the grid
		// gets a hidden heading for screen-reader navigation (the home.php
		// precedent) rather than a second display heading.
		?>
		<h2 class="visually-hidden">All projects</h2>

		<?php
		get_template_part( 'template-parts/work-filter', null, [
			'terms'  => $work_terms,
			'active' => $work_active,
		] );
		?>

		<?php if ( ! empty( $work_cards ) ) : ?>
			<div class="row g-4 work-grid" data-work-grid>
				<?php foreach ( $work_cards as $work_card ) :
					get_template_part( 'template-parts/work-card', null, [
						'project' => $work_card['id'],
						'pattern' => $work_card['pattern'],
						'hidden'  => ! $work_card['matches'],
					] );
				endforeach; ?>
			</div>
			<p class="work-empty" data-work-empty<?php echo $work_visible ? ' hidden' : ''; ?>>Nothing under that service yet. Choose another, or see all work.</p>
		<?php else : ?>
			<p class="work-empty">Projects are on their way.</p>
		<?php endif; ?>
	</div>
</section>

<?php
// The archive as a CollectionPage carries an ItemList of the project pages,
// in the same newest-first order as the grid (the services hub precedent).
if ( ! empty( $work_cards ) ) {
	$work_list_items = [];
	foreach ( $work_cards as $work_i => $work_card ) {
		$work_list_items[] = [
			'@type'    => 'ListItem',
			'position' => $work_i + 1,
			'name'     => get_field( 'pj_client_name', $work_card['id'] ) ?: get_the_title( $work_card['id'] ),
			'url'      => get_permalink( $work_card['id'] ),
		];
	}
	echo '<script type="application/ld+json">' . wp_json_encode( [
		'@context'        => 'https://schema.org',
		'@type'           => 'ItemList',
		'itemListElement' => $work_list_items,
	] ) . '</script>';
}

get_footer();
