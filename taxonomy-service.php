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

// Parent (pillar) terms become mini-hubs that list their child services; child
// (leaf) terms keep the full single-service layout. Only the deliverables vs
// children-grid section and the "related" set differ — everything else shared.
$is_pillar = ( 0 === (int) $term->parent );
$children  = $is_pillar ? vc_service_children( $term_id ) : array();

// Section headings are composed from three editable parts (start, red, end)
// by vc_heading_parts() in inc/template-functions.php; the red part renders
// as the standard <span> highlight. The hero fallback comes from the per-slug
// highlight map below, used when all three hero parts are blank.
$sv_heading_highlights = [
	'web-design-development' => 'Web Design & <span>Development</span>',
	'seo-ai-search'          => 'SEO & <span>AI Search</span>',
	'paid-media'             => 'Paid <span>Media</span>',
	'content-marketing'      => 'Content <span>Marketing</span>',
	'strategy-analytics'     => 'Strategy & <span>Analytics</span>',
	'branding'               => '<span>Branding</span>',
];
$sv_hero_fallback = isset( $sv_heading_highlights[ $term->slug ] ) ? $sv_heading_highlights[ $term->slug ] : esc_html( $term->name );
$hero_heading     = vc_heading_parts( 'sv_hero_heading', $acf_id, $sv_hero_fallback );
$hero_subheading  = get_field( 'sv_hero_subheading', $acf_id ) ?: $term_desc;

// Intro + deliverables
$intro_statement      = get_field( 'sv_intro_statement', $acf_id );
$intro_support        = get_field( 'sv_intro_support', $acf_id );
$deliverables_heading = vc_heading_parts( 'sv_deliverables_heading', $acf_id, 'What you <span>get</span>.' );
$children_heading     = vc_heading_parts( 'sv_children_heading', $acf_id, 'Explore our <span>services</span>.' );
$children_support     = get_field( 'sv_children_support', $acf_id );
$deliverables         = [];
if ( have_rows( 'sv_deliverables', $acf_id ) ) {
	while ( have_rows( 'sv_deliverables', $acf_id ) ) {
		the_row();
		$deliverables[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}

// Process: per-term steps, then the parent pillar's (for a leaf), then the
// sitewide homepage steps.
$process_heading    = vc_heading_parts( 'sv_process_heading', $acf_id, 'From brief to <span>results</span>.' );
$process_subheading = get_field( 'sv_process_subheading', $acf_id );
$process_steps      = [];
if ( have_rows( 'sv_process_steps', $acf_id ) ) {
	while ( have_rows( 'sv_process_steps', $acf_id ) ) {
		the_row();
		$process_steps[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}
// A leaf with no steps of its own inherits its parent pillar's process, so it
// never falls through to the generic homepage steps.
if ( ! $process_steps && ! $is_pillar && $term->parent && have_rows( 'sv_process_steps', 'service_' . $term->parent ) ) {
	while ( have_rows( 'sv_process_steps', 'service_' . $term->parent ) ) {
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
$results_heading = vc_heading_parts( 'sv_results_heading', $acf_id, 'The <span>numbers</span> behind it.' );
$results_stats   = [];
if ( have_rows( 'sv_results_stats', $acf_id ) ) {
	while ( have_rows( 'sv_results_stats', $acf_id ) ) {
		the_row();
		$results_stats[] = [ 'value' => get_sub_field('value'), 'label' => get_sub_field('label') ];
	}
}

// Related services: sibling pillars for a parent page (the shared Service List
// order), sibling children for a leaf page (alphabetical). Current term
// removed, first three.
$related_terms = $is_pillar ? vc_ordered_services() : vc_service_children( $term->parent );
$related_terms = array_values( array_filter( $related_terms, function ( $t ) use ( $term_id ) {
	return (int) $t->term_id !== (int) $term_id;
} ) );
$related_terms = array_slice( $related_terms, 0, 3 );

// Work + case studies + insights + related: editable three-part headings.
// Fallbacks stay short and generic — long service names made per-name
// headings unwieldy.
$work_heading         = vc_heading_parts( 'sv_work_heading', $acf_id, 'Recent <span>work</span>' );
$case_studies_heading = vc_heading_parts( 'sv_case_studies_heading', $acf_id, 'Case <span>studies</span>' );
$insights_heading     = vc_heading_parts( 'sv_insights_heading', $acf_id, 'Related <span>insights</span>' );
$related_heading      = vc_heading_parts( 'sv_related_heading', $acf_id, 'More ways we can <span>help</span>.' );

// Recent work: projects carrying this service term, shown on the shared
// homepage work wheel (the project CPT is admin-only content, queried
// directly). Tiles need an image; the section hides with no projects.
$work_query = new WP_Query([
	'post_type'      => 'project',
	'posts_per_page' => 8,
	'no_found_rows'  => true,
	'tax_query'      => [ [ 'taxonomy' => 'service', 'field' => 'term_id', 'terms' => $term_id ] ],
]);
$work_items = [];
if ( $work_query->have_posts() ) {
	while ( $work_query->have_posts() ) {
		$work_query->the_post();
		$pj_image = get_field( 'pj_image' );
		if ( ! $pj_image ) {
			continue;
		}
		$work_items[] = [
			'client'      => get_field( 'pj_client_name' ) ?: get_the_title(),
			'sector'      => get_field( 'pj_sector' ),
			'description' => get_field( 'pj_description' ),
			'image'       => $pj_image,
			// Tiles link to the project's own page (July 2026, the work-pages
			// build); the live-site link sits in that page's hero instead.
			'link'        => get_permalink(),
			// No service plate here: every tile is this service, so the
			// plates would all repeat the page title (Ibrar review).
			'service'     => '',
		];
	}
	wp_reset_postdata();
}

// Case studies proving this service: the newest imaged case studies carrying
// the term, on the shared metric-forward cards
// (template-parts/case-study-card.php). The section hides with none.
$case_studies_query = new WP_Query([
	'post_type'      => 'case_study',
	'posts_per_page' => 3,
	'no_found_rows'  => true,
	'tax_query'      => [ [ 'taxonomy' => 'service', 'field' => 'term_id', 'terms' => $term_id ] ],
]);
$case_study_items = [];
if ( $case_studies_query->have_posts() ) {
	while ( $case_studies_query->have_posts() ) {
		$case_studies_query->the_post();
		if ( ! get_field( 'cs_image' ) ) {
			continue;
		}
		$case_study_items[] = get_the_ID();
	}
	wp_reset_postdata();
}

// CTA
$cta_heading    = vc_heading_parts( 'sv_cta_heading', $acf_id, 'Ready to <span>start</span>?' );
$cta_subheading = get_field( 'sv_cta_subheading', $acf_id ) ?: "Tell us about your project and we'll reply within one working day with a clear next step.";

// FAQ: an accordion plus aggregated FAQPage schema (renders only with rows).
$faq_heading = vc_heading_parts( 'sv_faq_heading', $acf_id, 'Good to <span>know</span>.' );
$faqs        = [];
if ( have_rows( 'sv_faqs', $acf_id ) ) {
	while ( have_rows( 'sv_faqs', $acf_id ) ) {
		the_row();
		$faqs[] = [ 'question' => get_sub_field( 'question' ), 'answer' => get_sub_field( 'answer' ) ];
	}
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
	'cta_label'  => 'Start a Project',
	'cta_url'    => home_url( '/contact/' ),
] );
?>

<?php if ( ! $is_pillar && ( $deliverables || $intro_statement || $intro_support ) ) : ?>
<?php
// The welded lattice: the whole section is one fused hairline spec plate that
// assembles as you scroll — deliverables.js scrubs the weld rules drawing in,
// the red ticks igniting and the cell content rising, and scrolling back
// unwinds it. The heading composition lives INSIDE the plate (first cell,
// spanning two columns, with its own ember underline — unique to this
// section by standing decision). Every cell is always visible: the default
// state (no JS, reduced motion) is the fully assembled plate. No numerals —
// the journey below owns numbers.
?>
<section class="service-deliverables <?php echo esc_attr( $sv_surface() ); ?>" id="deliverables">
	<div class="container px-4">
		<div class="deliv-lattice">
			<div class="lattice-cell lattice-cell--head">
				<span class="weld weld-x" aria-hidden="true"></span>
				<span class="weld weld-hot weld-hot-x" aria-hidden="true"></span>
				<span class="weld weld-y" aria-hidden="true"></span>
				<span class="weld weld-hot weld-hot-y" aria-hidden="true"></span>
				<span class="weld-joint" aria-hidden="true"></span>
				<div class="cell-inner content">
					<h2><?php echo wp_kses_post( $deliverables_heading ); ?></h2>
					<span class="head-weld" aria-hidden="true"></span>
					<?php if ( $intro_statement ) : ?>
						<p class="lattice-statement"><?php echo wp_kses_post( $intro_statement ); ?></p>
					<?php endif; ?>
					<?php if ( $intro_support ) : ?>
						<p class="lattice-support"><?php echo esc_html( $intro_support ); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php foreach ( $deliverables as $deliv ) : ?>
				<div class="lattice-cell">
					<span class="weld weld-x" aria-hidden="true"></span>
					<span class="weld weld-hot weld-hot-x" aria-hidden="true"></span>
					<span class="weld weld-y" aria-hidden="true"></span>
					<span class="weld weld-hot weld-hot-y" aria-hidden="true"></span>
					<span class="weld-joint" aria-hidden="true"></span>
					<div class="cell-inner">
						<h3 class="cell-title"><?php echo esc_html( $deliv['title'] ); ?></h3>
						<?php if ( $deliv['description'] ) : ?>
							<p class="cell-desc"><?php echo esc_html( $deliv['description'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="lattice-cell lattice-cell--cta">
				<span class="weld weld-x" aria-hidden="true"></span>
				<span class="weld weld-hot weld-hot-x" aria-hidden="true"></span>
				<span class="weld weld-y" aria-hidden="true"></span>
				<span class="weld weld-hot weld-hot-y" aria-hidden="true"></span>
				<span class="weld-joint" aria-hidden="true"></span>
				<div class="cell-inner">
					<p class="cell-nudge">Scoped to your goals, priced before we start.</p>
					<a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $is_pillar && $children ) : ?>
<?php
// Pillar pages list their child services as cards — the hub grid pattern on a
// service term page, with a split head (heading + intro-lead). The child cards
// are the shared service-card partial; children are alphabetical.
?>
<section class="service-children <?php echo esc_attr( $sv_surface() ); ?>" id="services">
	<div class="container px-4">
		<div class="row service-children-head">
			<div class="col-lg-6">
				<div class="content">
					<h2><?php echo wp_kses_post( $children_heading ); ?></h2>
				</div>
			</div>
			<?php if ( $intro_statement || $children_support ) : ?>
			<div class="col-lg-5 offset-lg-1">
				<div class="intro-lead">
					<?php if ( $intro_statement ) : ?>
						<p class="intro-statement"><?php echo wp_kses_post( $intro_statement ); ?></p>
					<?php endif; ?>
					<?php if ( $children_support ) : ?>
						<p class="intro-support"><?php echo esc_html( $children_support ); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="row g-4 services-grid">
			<?php foreach ( $children as $child ) : ?>
				<div class="col-lg-4 col-md-6 col-12 service-card-col">
					<?php get_template_part( 'template-parts/service', 'card', [
						'term'       => $child,
						'variant'    => 'grid',
						'show_index' => false,
					] ); ?>
				</div>
			<?php endforeach; ?>
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
					<h2><?php echo wp_kses_post( $process_heading ); ?></h2>
					<?php if ( $process_subheading ) : ?>
						<p class="sub-heading"><?php echo esc_html( $process_subheading ); ?></p>
					<?php endif; ?>
				</div>
				<span class="journey-counter" aria-hidden="true"><span class="counter-current"><?php echo esc_html( $journey_total ); ?></span> / <span class="counter-total"><?php echo esc_html( $journey_total ); ?></span></span>
			</div>
			<div class="journey-rail">
				<div class="journey-track">
					<span class="journey-progress" aria-hidden="true"></span>
					<span class="journey-terminus" aria-hidden="true"></span>
					<?php $step_i = 1; foreach ( $process_steps as $step ) : ?>
						<div class="journey-step">
							<?php // The numeral is the point: it sits on a knockout plate on the line. ?>
							<span class="step-marker" aria-hidden="true">
								<span class="step-index"><?php echo str_pad( $step_i, 2, '0', STR_PAD_LEFT ); ?></span>
							</span>
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
// Full-dark forge anchor (#121212 light, #0D0D0D dark so it always differs
// from the #121212 journey above): it punctuates the page rather than taking
// an alternation slot, so it deliberately does not consume $sv_surface().
// counter.js binds the .results / .stat-number classes unchanged.
?>
<section class="results service-results results-anchor" id="results">
	<div class="container px-4">
		<div class="content">
			<h2><?php echo wp_kses_post( $results_heading ); ?></h2>
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
		<div class="anchor-actions">
			<a class="button-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $work_items ) : ?>
<section class="service-work <?php echo esc_attr( $sv_surface() ); ?>" id="work">
	<div class="container px-4">
		<?php
		// Recent work in this service: the shared homepage work wheel
		// (template-parts/work-wheel.php + homepage/our-work.js, shipped in
		// the service bundle), fed by the term-filtered projects above.
		get_template_part( 'template-parts/work-wheel', null, [
			'projects'    => $work_items,
			'heading'     => $work_heading,
			'stage_label' => 'Recent ' . $term->name . ' projects',
		] );
		?>
	</div>
</section>
<?php endif; ?>

<?php if ( $case_study_items ) : ?>
<section class="service-case-studies <?php echo esc_attr( $sv_surface() ); ?>" id="case-studies">
	<div class="container px-4">
		<div class="service-case-studies-head">
			<div class="content">
				<h2><?php echo wp_kses_post( $case_studies_heading ); ?></h2>
			</div>
		</div>
		<?php // The shared metric-forward cards, three-up (the /case-studies/ archive family). ?>
		<div class="row g-4 case-studies-row">
			<?php foreach ( $case_study_items as $case_study_id ) :
				get_template_part( 'template-parts/case-study-card', null, [
					'case'  => $case_study_id,
					'class' => 'col-12 col-md-6 col-lg-4',
				] );
			endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( have_posts() ) : ?>
<section class="service-insights <?php echo esc_attr( $sv_surface() ); ?>" id="insights">
	<div class="container px-4">
		<div class="service-insights-head">
			<div class="content">
				<h2><?php echo wp_kses_post( $insights_heading ); ?></h2>
			</div>
		</div>
		<?php // The shared insight cards — the homepage latest-insights pattern. ?>
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
			<h2><?php echo wp_kses_post( $related_heading ); ?></h2>
		</div>
		<div class="row g-4 services-grid">
			<?php
			$related_i = 1;
			foreach ( $related_terms as $related_term ) {
				echo '<div class="col-lg-4 col-md-6 col-12 service-card-col">';
				get_template_part( 'template-parts/service', 'card', [
					'term'       => $related_term,
					'index'      => $related_i,
					'variant'    => 'grid',
					'show_index' => false,
				] );
				echo '</div>';
				$related_i++;
			}
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $faqs ) : ?>
<section class="service-faq <?php echo esc_attr( $sv_surface() ); ?>" id="faq">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-4">
				<div class="service-faq-head">
					<div class="content">
						<h2><?php echo wp_kses_post( $faq_heading ); ?></h2>
					</div>
					<a class="button-ghost" href="#enquire">Start a Project</a>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<div class="service-faq-list">
					<?php foreach ( $faqs as $i => $faq ) :
						$q_id = 'sv-faq-q-' . ( $i + 1 );
						$a_id = 'sv-faq-a-' . ( $i + 1 ); ?>
						<div class="service-faq-item">
							<h3 class="faq-q">
								<button type="button" class="faq-toggle" id="<?php echo esc_attr( $q_id ); ?>" aria-expanded="true" aria-controls="<?php echo esc_attr( $a_id ); ?>">
									<span class="faq-q-text"><?php echo esc_html( $faq['question'] ); ?></span>
									<span class="faq-icon" aria-hidden="true"></span>
								</button>
							</h3>
							<div class="faq-a" id="<?php echo esc_attr( $a_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $q_id ); ?>">
								<div class="faq-a-inner"><?php echo wpautop( esc_html( $faq['answer'] ) ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="service-cta <?php echo esc_attr( $sv_surface() ); ?>" id="enquire">
	<?php // Oversized outlined service name as the closing brand moment (the footer wordmark recipe). ?>
	<span class="cta-wordmark" aria-hidden="true"><?php echo esc_html( $term->name ); ?></span>
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-6">
				<div class="content">
					<h2><?php echo wp_kses_post( $cta_heading ); ?></h2>
					<p class="sub-heading"><?php echo esc_html( $cta_subheading ); ?></p>
				</div>
				<div class="cta-actions">
					<a class="button-ghost" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">All Services</a>
				</div>
			</div>
			<div class="col-lg-6 form">
				<div class="form-container">
					<?php vc_render_form( 2, 'service_' . $term->term_id ); ?>
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

// FAQPage node from the service FAQs (aggregated), for rich results and AI search.
$faq_entities = [];
foreach ( $faqs as $faq ) {
	$q = trim( wp_strip_all_tags( (string) $faq['question'] ) );
	$a = trim( wp_strip_all_tags( (string) $faq['answer'] ) );
	if ( '' === $q || '' === $a ) {
		continue;
	}
	$faq_entities[] = [ '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $a ] ];
}
if ( $faq_entities ) {
	echo '<script type="application/ld+json">'
		. wp_json_encode( [ '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq_entities ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>';
}

get_footer();
