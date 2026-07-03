<?php
/**
 * Service page: one `service` taxonomy term rendered as a full service page
 * at /services/{slug}/. Content comes from term-level ACF fields (the
 * "Service Page" group, sv_ prefix); the capped main query (inc/actions.php)
 * feeds the insights grid. Every rendered section takes the next surface
 * class from $sv_surface(), so the light/dark pairing rule survives the
 * conditional sections.
 */
get_header();

$term      = get_queried_object();
$term_id   = $term->term_id;
$acf_id    = 'service_' . $term_id;
$term_link = get_term_link( $term );
$term_desc = wp_strip_all_tags( term_description( $term_id ) );

// The homepage is the single source for the sitewide process steps.
$front_page_id = (int) get_option( 'page_on_front' );

// Heading highlight map, keyed by term slug: used when the editor has not
// set their own hero heading (the ct_heading()/ab_heading() pattern).
$sv_heading_highlights = [
	'web-design-development' => 'Web Design & <span>Development</span>',
	'seo-ai-search'          => 'SEO & <span>AI Search</span>',
	'digital-marketing'      => 'Digital <span>Marketing</span>',
	'paid-search-ppc'        => 'Paid Search & <span>PPC</span>',
	'content-creation'       => 'Content <span>Creation</span>',
	'branding'               => '<span>Branding</span>',
];
$hero_heading = get_field( 'sv_hero_heading', $acf_id );
if ( ! $hero_heading || $hero_heading === $term->name ) {
	$hero_heading = isset( $sv_heading_highlights[ $term->slug ] ) ? $sv_heading_highlights[ $term->slug ] : esc_html( $term->name );
}
$hero_subheading = get_field( 'sv_hero_subheading', $acf_id ) ?: $term_desc;

// Intro + deliverables
$intro_statement      = get_field( 'sv_intro_statement', $acf_id );
$intro_support        = get_field( 'sv_intro_support', $acf_id );
$deliverables_heading = get_field( 'sv_deliverables_heading', $acf_id ) ?: 'What you get.';
$deliverables         = [];
if ( have_rows( 'sv_deliverables', $acf_id ) ) {
	while ( have_rows( 'sv_deliverables', $acf_id ) ) {
		the_row();
		$deliverables[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}

// Process: per-term steps, falling back to the sitewide homepage steps.
$process_heading    = get_field( 'sv_process_heading', $acf_id ) ?: 'How this engagement runs.';
$process_subheading = get_field( 'sv_process_subheading', $acf_id );
$process_steps      = [];
if ( have_rows( 'sv_process_steps', $acf_id ) ) {
	while ( have_rows( 'sv_process_steps', $acf_id ) ) {
		the_row();
		$process_steps[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}
if ( ! $process_steps && have_rows( 'hp_process_steps', $front_page_id ) ) {
	while ( have_rows( 'hp_process_steps', $front_page_id ) ) {
		the_row();
		$process_steps[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}

// Results: real figures only; the section renders only when rows exist.
$results_heading = get_field( 'sv_results_heading', $acf_id ) ?: 'The numbers behind it.';
$results_stats   = [];
if ( have_rows( 'sv_results_stats', $acf_id ) ) {
	while ( have_rows( 'sv_results_stats', $acf_id ) ) {
		the_row();
		$results_stats[] = [ 'value' => get_sub_field('value'), 'label' => get_sub_field('label') ];
	}
}

// Related services: the other terms, in the shared order, first three.
$related_terms = get_terms([
	'taxonomy'   => 'service',
	'hide_empty' => false,
]);
if ( is_wp_error( $related_terms ) ) {
	$related_terms = [];
}
foreach ( $related_terms as $key => $related_term ) {
	if ( $related_term->term_id === $term_id ) {
		unset( $related_terms[ $key ] );
		continue;
	}
	$related_terms[ $key ]->order = (int) get_field( 'order', 'service_' . $related_term->term_id );
}
usort( $related_terms, function ( $a, $b ) {
	return $a->order - $b->order;
} );
$related_terms = array_slice( $related_terms, 0, 3 );

// CTA (default heading gets the red highlight via the usual map pattern)
$cta_heading = get_field( 'sv_cta_heading', $acf_id );
if ( ! $cta_heading || 'Ready to start?' === $cta_heading ) {
	$cta_heading = 'Ready to <span>start</span>?';
}
$cta_subheading = get_field( 'sv_cta_subheading', $acf_id ) ?: "Tell us about your project and we'll reply within one working day with a clear next step.";

$insights_blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
if ( ! $insights_blog_url ) {
	$insights_blog_url = home_url( '/blog/' );
}

// Alternating surface classes: every rendered section consumes a slot, so the
// pairing rule (first section after the hero is the lighter pair) survives
// the conditional sections.
$sv_surface = function () {
	static $i = 0;
	return ( $i++ % 2 === 0 ) ? 'surface-white' : 'surface-grey';
};
?>

<?php
// The shared page-hero band; 'service-hero' stays on the section as the alias
// the pre-hide rules in service/_service.scss and service/reveal.js target.
get_template_part( 'template-parts/page', 'hero', [
	'heading'    => $hero_heading,
	'subheading' => $hero_subheading,
	'class'      => 'service-hero',
] );
?>

<?php if ( $deliverables || $intro_statement || $intro_support ) : ?>
<section class="service-deliverables <?php echo esc_attr( $sv_surface() ); ?>" id="deliverables">
	<div class="container px-4">
		<div class="row">
			<div class="col-lg-4">
				<div class="deliv-head">
					<div class="content">
						<h2><?php echo esc_html( $deliverables_heading ); ?></h2>
					</div>
					<?php if ( $intro_statement || $intro_support ) : ?>
						<div class="intro-lead">
							<?php if ( $intro_statement ) : ?>
								<p class="intro-statement"><?php echo wp_kses_post( $intro_statement ); ?></p>
							<?php endif; ?>
							<?php if ( $intro_support ) : ?>
								<p class="intro-support"><?php echo esc_html( $intro_support ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<?php if ( $deliverables ) : ?>
					<div class="deliv-rail-wrap">
						<span class="deliv-progress" aria-hidden="true"></span>
						<ul class="deliv-rows">
							<?php foreach ( $deliverables as $deliv_i => $deliv ) : ?>
								<li class="deliv-row">
									<span class="deliv-index" aria-hidden="true"><?php echo esc_html( str_pad( $deliv_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
									<span class="deliv-body">
										<h3 class="deliv-title"><?php echo esc_html( $deliv['title'] ); ?></h3>
										<?php if ( $deliv['description'] ) : ?>
											<p class="deliv-desc"><?php echo esc_html( $deliv['description'] ); ?></p>
										<?php endif; ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $process_steps ) : ?>
<?php
// The engagement journey: this page's signature interaction. On desktop with
// motion allowed, journey.js promotes it (is-journey) to a sticky sideways
// travel — milestone plates on one continuous ember line, numerals igniting
// as the tip sweeps them, the counter ticking in the head. Everywhere else
// (below lg, reduced motion, no JS) the same markup is a vertical ledger in
// its finished state. The counter is server-rendered complete for the no-JS
// state; journey.js rewinds it inside its motion contexts.
$journey_total = str_pad( count( $process_steps ), 2, '0', STR_PAD_LEFT );
?>
<section class="service-journey <?php echo esc_attr( $sv_surface() ); ?>" id="process">
	<div class="journey-viewport">
		<div class="container px-4">
			<div class="journey-head">
				<div class="content">
					<h2><?php echo esc_html( $process_heading ); ?></h2>
					<?php if ( $process_subheading ) : ?>
						<p class="sub-heading"><?php echo esc_html( $process_subheading ); ?></p>
					<?php endif; ?>
				</div>
				<span class="journey-counter" aria-hidden="true"><span class="counter-current"><?php echo esc_html( $journey_total ); ?></span>&nbsp;/&nbsp;<span class="counter-total"><?php echo esc_html( $journey_total ); ?></span></span>
			</div>
			<div class="journey-rail">
				<div class="journey-track">
					<span class="journey-progress" aria-hidden="true"></span>
					<?php $step_i = 1; foreach ( $process_steps as $step ) : ?>
						<div class="journey-step">
							<span class="step-index" aria-hidden="true"><?php echo str_pad( $step_i, 2, '0', STR_PAD_LEFT ); ?></span>
							<span class="step-node" aria-hidden="true"></span>
							<div class="step-body">
								<h3><?php echo esc_html( $step['title'] ); ?></h3>
								<p><?php echo esc_html( $step['description'] ); ?></p>
							</div>
						</div>
					<?php $step_i++; endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $results_stats ) : ?>
<?php
// Full-dark forge anchor (#121212 in both modes, the homepage why-band
// precedent): it punctuates the page rather than taking an alternation slot,
// so it deliberately does not consume $sv_surface(). counter.js binds the
// .results / .stat-number classes unchanged.
?>
<section class="results service-results results-anchor" id="results">
	<div class="container px-4">
		<div class="content">
			<h2><?php echo esc_html( $results_heading ); ?></h2>
		</div>
		<div class="row gx-4 gy-4 stats-grid">
			<?php $stat_col = count( $results_stats ) > 3 ? 'col-lg-3' : 'col-lg-4'; ?>
			<?php foreach ( $results_stats as $stat ) : ?>
				<div class="<?php echo esc_attr( $stat_col ); ?> col-6">
					<div class="stat">
						<span class="stat-number"><?php echo esc_html( $stat['value'] ); ?></span>
						<p class="stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( have_posts() ) : ?>
<section class="service-insights <?php echo esc_attr( $sv_surface() ); ?>" id="insights">
	<div class="container px-4">
		<div class="service-insights-head">
			<div class="content">
				<h2>Insights on <?php echo esc_html( $term->name ); ?></h2>
			</div>
			<a class="insights-all-link" href="<?php echo esc_url( $insights_blog_url ); ?>">All insights</a>
		</div>
		<div class="row g-4 insights-row">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'card' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $related_terms ) : ?>
<section class="service-related <?php echo esc_attr( $sv_surface() ); ?>" id="related">
	<div class="container px-4">
		<div class="content">
			<h2>More ways we can help.</h2>
		</div>
		<div class="row g-4 services-grid">
			<?php
			$related_i = 1;
			foreach ( $related_terms as $related_term ) {
				echo '<div class="col-lg-4 col-md-6 col-12 service-card-col">';
				get_template_part( 'template-parts/service', 'card', [
					'term'    => $related_term,
					'index'   => $related_i,
					'variant' => 'related',
				] );
				echo '</div>';
				$related_i++;
			}
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="service-cta <?php echo esc_attr( $sv_surface() ); ?>" id="enquire">
	<div class="container px-4">
		<div class="row">
			<div class="col-lg-8">
				<div class="content">
					<h2><?php echo wp_kses_post( $cta_heading ); ?></h2>
					<p class="sub-heading"><?php echo esc_html( $cta_subheading ); ?></p>
				</div>
				<div class="cta-actions">
					<a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a>
					<a class="button-ghost" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">All services</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
// Service node attached to Yoast's Organization (@id /#organization), the
// About Person-nodes pattern; the hub's ItemList links to this URL.
if ( ! is_wp_error( $term_link ) ) {
	echo '<script type="application/ld+json">'
		. wp_json_encode( [
			'@context'    => 'https://schema.org',
			'@type'       => 'Service',
			'@id'         => $term_link . '#service',
			'name'        => $term->name,
			'serviceType' => $term->name,
			'description' => $term_desc,
			'url'         => $term_link,
			'provider'    => [ '@id' => home_url( '/#organization' ) ],
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>';
}

get_footer();
