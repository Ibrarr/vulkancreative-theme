<?php
/**
 * Single case study at /case-studies/{slug}/ (case_study post type). The
 * proof tier above the /work/ showcases: a project-led hero on the dark forge
 * band (breadcrumbs, client name, meta row, the headline result at hero
 * scale, large media panel), then the story: overview (statement, fact
 * ledger), challenge, approach (narrative and editorial gallery), the results
 * band with counted-up stats, the client's testimonial, the linked project
 * and services, and the closing CTA band (no form: the spokes rule;
 * footer.php hides the footer CTA here). Optional fields hide their sections,
 * so lighter case studies render fewer sections on the same template. Shared
 * strings live on the Case Studies Page options page (csp_ fields);
 * per-case-study content is the cs_ fields. The linked project is resolved by
 * reverse meta query: pj_case_study on the project is the single source of
 * truth for the pairing.
 */

get_header();

while ( have_posts() ) :
	the_post();

	$cs_id           = get_the_ID();
	$cs_client       = get_field( 'cs_client_name' ) ?: get_the_title();
	$cs_sector       = get_field( 'cs_sector' );
	$cs_summary      = get_field( 'cs_summary' );
	$cs_metric_value = get_field( 'cs_metric_value' );
	$cs_metric_label = get_field( 'cs_metric_label' );
	$cs_image        = get_field( 'cs_image' );
	$cs_year         = get_field( 'cs_year' );
	$cs_ov_statement = get_field( 'cs_overview_statement' );
	$cs_ov_support   = get_field( 'cs_overview_support' );
	$cs_ch_statement = get_field( 'cs_challenge_statement' );
	$cs_ch_body      = get_field( 'cs_challenge_body' );
	$cs_ap_body      = get_field( 'cs_approach_body' );
	$cs_gallery      = get_field( 'cs_approach_gallery' ) ?: [];
	$cs_stats        = get_field( 'cs_results_stats' ) ?: [];
	$cs_narrative    = get_field( 'cs_results_narrative' );
	$cs_testimonial  = (int) get_field( 'cs_testimonial' );

	$cs_terms = get_the_terms( $cs_id, 'service' );
	$cs_terms = ( $cs_terms && ! is_wp_error( $cs_terms ) ) ? $cs_terms : [];

	// The documented project: pj_case_study on the project is the single
	// source of truth, so the case study finds its project by reverse query.
	$cs_project_ids = get_posts( [
		'post_type'      => 'project',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_key'       => 'pj_case_study',
		'meta_value'     => (string) $cs_id,
	] );
	$cs_project_id  = $cs_project_ids ? (int) $cs_project_ids[0] : 0;
	$cs_project_url = $cs_project_id ? get_permalink( $cs_project_id ) : '';

	// The client's testimonial, from the shared testimonial CPT.
	$cs_tm = null;
	if ( $cs_testimonial && 'publish' === get_post_status( $cs_testimonial ) ) {
		$cs_tm_photo = get_field( 'tm_photo', $cs_testimonial );
		$cs_tm       = [
			'quote'   => get_field( 'tm_quote', $cs_testimonial ),
			'name'    => get_field( 'tm_name', $cs_testimonial ),
			'company' => trim( get_field( 'tm_role', $cs_testimonial ) . ', ' . get_field( 'tm_company', $cs_testimonial ), ', ' ),
			'photo'   => $cs_tm_photo['sizes']['large'] ?? $cs_tm_photo['url'] ?? '',
		];
		if ( ! $cs_tm['quote'] ) {
			$cs_tm = null;
		}
	}

	// Alternating light surfaces below the hero (the work-single closure):
	// conditional sections simply hand their slot down. The results band sits
	// outside the alternation on the full-dark anchor surface.
	$cs_surface_i = 0;
	$cs_surface   = function () use ( &$cs_surface_i ) {
		return ( $cs_surface_i++ % 2 === 0 ) ? 'surface-white' : 'surface-grey';
	};
	?>

	<section class="page-hero cs-hero" id="top">
		<div class="page-hero-glow" aria-hidden="true"></div>
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-9 content">
					<div class="breadcrumbs"><?php echo do_shortcode( '[wpseo_breadcrumb]' ); ?></div>
					<h1><?php echo esc_html( $cs_client ); ?></h1>
					<?php if ( $cs_summary ) : ?>
						<p class="sub-heading"><?php echo esc_html( $cs_summary ); ?></p>
					<?php endif; ?>
					<?php if ( $cs_sector || $cs_year || $cs_terms ) : ?>
						<ul class="hero-meta">
							<?php if ( $cs_sector ) : ?><li><?php echo esc_html( $cs_sector ); ?></li><?php endif; ?>
							<?php if ( $cs_year ) : ?><li><?php echo esc_html( $cs_year ); ?></li><?php endif; ?>
							<?php if ( $cs_terms ) : ?>
								<li class="hero-meta-services">
									<?php foreach ( $cs_terms as $meta_i => $meta_term ) :
										$meta_link = get_term_link( $meta_term );
										if ( is_wp_error( $meta_link ) ) {
											continue;
										}
										?><a href="<?php echo esc_url( $meta_link ); ?>"><?php echo esc_html( $meta_term->name ); ?></a><?php echo $meta_i < count( $cs_terms ) - 1 ? '<span aria-hidden="true">, </span>' : ''; ?><?php
									endforeach; ?>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $cs_metric_value ) : ?>
						<?php // The headline result at hero scale: the number is the page's reason to exist. The project link lives below (the fact ledger row and the full-story panel), so the hero stays lean. ?>
						<div class="hero-metric">
							<span class="hero-metric-value"><?php echo esc_html( $cs_metric_value ); ?></span>
							<?php if ( $cs_metric_label ) : ?>
								<span class="hero-metric-label"><?php echo esc_html( $cs_metric_label ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $cs_image ) : ?>
				<div class="hero-media">
					<?php echo wp_get_attachment_image( $cs_image['ID'], 'header-image', false, [
						'class'         => 'hero-media-img',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					] ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="cs-overview <?php echo esc_attr( $cs_surface() ); ?>">
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-6">
					<div class="content">
						<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_overview_heading', 'options', 'At a <span>glance</span>.' ) ); ?></h2>
					</div>
					<?php if ( $cs_ov_statement || $cs_ov_support || $cs_summary ) : ?>
						<div class="overview-lead">
							<p class="overview-statement"><?php echo esc_html( $cs_ov_statement ?: $cs_summary ); ?></p>
							<?php if ( $cs_ov_support ) : ?>
								<p class="overview-support"><?php echo esc_html( $cs_ov_support ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<dl class="fact-ledger">
						<div class="fact-row">
							<dt>Client</dt>
							<dd><?php echo esc_html( $cs_client ); ?></dd>
						</div>
						<?php if ( $cs_sector ) : ?>
							<div class="fact-row">
								<dt>Sector</dt>
								<dd><?php echo esc_html( $cs_sector ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $cs_year ) : ?>
							<div class="fact-row">
								<dt>Year</dt>
								<dd><?php echo esc_html( $cs_year ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $cs_terms ) : ?>
							<div class="fact-row">
								<dt>Services</dt>
								<dd>
									<?php foreach ( $cs_terms as $fact_i => $fact_term ) :
										$fact_link = get_term_link( $fact_term );
										if ( is_wp_error( $fact_link ) ) {
											continue;
										}
										?><a href="<?php echo esc_url( $fact_link ); ?>"><?php echo esc_html( $fact_term->name ); ?></a><?php echo $fact_i < count( $cs_terms ) - 1 ? '<span aria-hidden="true">, </span>' : ''; ?><?php
									endforeach; ?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $cs_project_url ) : ?>
							<div class="fact-row">
								<dt>Project</dt>
								<dd><a href="<?php echo esc_url( $cs_project_url ); ?>"><?php echo esc_html( get_field( 'pj_client_name', $cs_project_id ) ?: get_the_title( $cs_project_id ) ); ?></a></dd>
							</div>
						<?php endif; ?>
					</dl>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $cs_ch_statement || $cs_ch_body ) : ?>
		<section class="cs-challenge <?php echo esc_attr( $cs_surface() ); ?>">
			<div class="container px-4">
				<div class="row">
					<div class="col-lg-8">
						<div class="content">
							<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_challenge_heading', 'options', 'The <span>challenge</span>.' ) ); ?></h2>
						</div>
					</div>
				</div>
				<?php // The bold statement pulls to the right rail on desktop (above the body on mobile), so the measure stays comfortable without an empty column. ?>
				<div class="row gy-4 narrative-row">
					<?php if ( $cs_ch_statement ) : ?>
						<div class="col-lg-4 offset-lg-1 order-lg-2">
							<p class="statement"><?php echo esc_html( $cs_ch_statement ); ?></p>
						</div>
					<?php endif; ?>
					<?php if ( $cs_ch_body ) : ?>
						<div class="col-lg-7 order-lg-1">
							<div class="narrative-body"><?php echo wp_kses_post( $cs_ch_body ); ?></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// The paired project's deliverables fill the approach's right rail: the
	// data already lives on the project, so nothing is retyped per case study.
	$cs_deliverables = $cs_project_id ? ( get_field( 'pj_deliverables', $cs_project_id ) ?: [] ) : [];
	?>
	<?php if ( $cs_ap_body || ! empty( $cs_gallery ) ) : ?>
		<section class="cs-approach <?php echo esc_attr( $cs_surface() ); ?>">
			<div class="container px-4">
				<div class="row">
					<div class="col-lg-8">
						<div class="content">
							<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_approach_heading', 'options', 'The <span>approach</span>.' ) ); ?></h2>
						</div>
					</div>
				</div>
				<div class="row gy-4 narrative-row">
					<?php if ( $cs_ap_body ) : ?>
						<div class="col-lg-7">
							<div class="narrative-body"><?php echo wp_kses_post( $cs_ap_body ); ?></div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $cs_deliverables ) ) : ?>
						<div class="col-lg-4 offset-lg-1">
							<div class="deliverables">
								<p class="deliverables-label">What we delivered</p>
								<ul class="deliverables-list">
									<?php foreach ( $cs_deliverables as $cs_deliverable ) : ?>
										<li><?php echo esc_html( $cs_deliverable['label'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $cs_gallery ) ) : ?>
					<div class="row g-4 gallery-grid">
						<?php foreach ( $cs_gallery as $gallery_i => $gallery_row ) :
							if ( empty( $gallery_row['image'] ) ) {
								continue;
							}
							$gallery_image   = $gallery_row['image'];
							$gallery_caption = $gallery_row['caption'] ?? '';
							// Rhythm per group of three: a full-width panel, then a
							// 7/5 pair (the work-single gallery's editorial family).
							$gallery_slot  = $gallery_i % 3;
							$gallery_class = 'col-12 gallery-item--full';
							if ( 1 === $gallery_slot ) {
								$gallery_class = 'col-12 col-lg-7';
							} elseif ( 2 === $gallery_slot ) {
								$gallery_class = 'col-12 col-lg-5';
							}
							?>
							<figure class="gallery-item <?php echo esc_attr( $gallery_class ); ?>">
								<span class="gallery-media">
									<?php echo wp_get_attachment_image( $gallery_image['ID'], 'header-image', false, [
										'class'   => 'gallery-img',
										'loading' => 'lazy',
										'alt'     => $gallery_caption ? '' : ( $gallery_image['alt'] ?? '' ),
									] ); ?>
								</span>
								<?php if ( $gallery_caption ) : ?>
									<figcaption><?php echo esc_html( $gallery_caption ); ?></figcaption>
								<?php endif; ?>
							</figure>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $cs_stats ) || $cs_narrative ) : ?>
		<?php
		// The results band punctuates the story on the full-dark anchor
		// surface (no alternation slot). Base .results classes keep
		// counter.js binding to .stat-number unchanged.
		$cs_stat_col = count( $cs_stats ) >= 4 ? 'col-lg-3 col-6' : 'col-lg-4 col-6';
		?>
		<section class="cs-results results">
			<div class="container px-4">
				<div class="content">
					<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_results_heading', 'options', 'The <span>results</span>.' ) ); ?></h2>
				</div>
				<?php if ( ! empty( $cs_stats ) ) : ?>
					<div class="row gx-4 gy-4 stats-grid">
						<?php foreach ( $cs_stats as $cs_stat ) : ?>
							<div class="<?php echo esc_attr( $cs_stat_col ); ?>">
								<div class="stat">
									<span class="stat-number"><?php echo esc_html( $cs_stat['value'] ); ?></span>
									<p class="stat-label"><?php echo esc_html( $cs_stat['label'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( $cs_narrative ) : ?>
					<p class="results-narrative"><?php echo esc_html( $cs_narrative ); ?></p>
				<?php endif; ?>
				<div class="results-actions">
					<a class="button-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $cs_tm ) : ?>
		<section class="cs-testimonial <?php echo esc_attr( $cs_surface() ); ?>">
			<div class="container px-4">
				<div class="row">
					<div class="col-lg-8">
						<div class="content">
							<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_testimonial_heading', 'options', 'In their <span>words</span>.' ) ); ?></h2>
						</div>
					</div>
				</div>
				<?php // The shared spotlight anatomy in its static variant: one quote, no carousel. ?>
				<div class="testimonial-spotlight spotlight-static<?php echo $cs_tm['photo'] ? '' : ' spotlight-static--no-photo'; ?>">
					<div class="spotlight-layout">
						<?php if ( $cs_tm['photo'] ) : ?>
							<div class="spotlight-photo" aria-hidden="true">
								<img class="spotlight-portrait is-active" loading="lazy" src="<?php echo esc_url( $cs_tm['photo'] ); ?>" alt="">
							</div>
						<?php endif; ?>
						<div class="spotlight-main">
							<div class="spotlight-mark" aria-hidden="true">&ldquo;</div>
							<blockquote>
								<p class="spotlight-quote"><?php echo esc_html( $cs_tm['quote'] ); ?></p>
								<cite>
									<span class="cite-text">
										<span class="t-name"><?php echo esc_html( $cs_tm['name'] ); ?></span>
										<span class="t-company"><?php echo esc_html( $cs_tm['company'] ); ?></span>
									</span>
								</cite>
							</blockquote>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $cs_project_id || $cs_terms ) : ?>
		<section class="cs-related <?php echo esc_attr( $cs_surface() ); ?>">
			<div class="container px-4">
				<div class="row">
					<div class="col-lg-8">
						<div class="content">
							<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_related_heading', 'options', 'The <span>work</span> behind it.' ) ); ?></h2>
						</div>
					</div>
				</div>
				<?php if ( $cs_project_id ) :
					$cs_pj_client = get_field( 'pj_client_name', $cs_project_id ) ?: get_the_title( $cs_project_id );
					$cs_pj_line   = get_field( 'pj_description', $cs_project_id );
					$cs_pj_image  = get_field( 'pj_image', $cs_project_id );

					// Keep the arrow glued to the last word of the name.
					$cs_pj_words = explode( ' ', $cs_pj_client );
					$cs_pj_last  = array_pop( $cs_pj_words );
					$cs_pj_head  = implode( ' ', $cs_pj_words );
					?>
					<a class="related-project" href="<?php echo esc_url( $cs_project_url ); ?>">
						<span class="related-project-eyebrow">The project</span>
						<?php if ( $cs_pj_image ) : ?>
							<span class="related-project-media">
								<?php echo wp_get_attachment_image( $cs_pj_image['ID'], 'large', false, [
									'class'   => 'related-project-img',
									'loading' => 'lazy',
									'alt'     => '',
								] ); ?>
							</span>
						<?php endif; ?>
						<h3 class="related-project-client"><?php echo $cs_pj_head ? esc_html( $cs_pj_head ) . ' ' : ''; ?><span class="related-project-end"><?php echo esc_html( $cs_pj_last ); ?><svg class="related-project-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span></h3>
						<?php if ( $cs_pj_line ) : ?>
							<span class="related-project-line"><?php echo esc_html( $cs_pj_line ); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
				<?php if ( $cs_terms ) : ?>
					<?php
					// The cards fill the row: three terms sit three-up, one or
					// two widen to halves so no empty column is left behind.
					$cs_related_terms = array_slice( $cs_terms, 0, 3 );
					$cs_term_col      = count( $cs_related_terms ) >= 3 ? 'col-lg-4 col-md-6 col-12' : 'col-lg-6 col-md-6 col-12';
					?>
					<div class="row g-4 related-services-row">
						<?php foreach ( $cs_related_terms as $cs_term_i => $cs_term ) : ?>
							<div class="<?php echo esc_attr( $cs_term_col ); ?>">
								<?php
								get_template_part( 'template-parts/service-card', null, [
									'term'       => $cs_term,
									'index'      => $cs_term_i + 1,
									'variant'    => 'related',
									'show_index' => false,
								] );
								?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="cs-cta <?php echo esc_attr( $cs_surface() ); ?>" id="enquire">
		<?php // Oversized outlined client name as the closing brand moment (the work-single wordmark recipe). ?>
		<span class="cta-wordmark" aria-hidden="true"><?php echo esc_html( $cs_client ); ?></span>
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-8">
					<div class="content">
						<h2><?php echo wp_kses_post( vc_heading_parts( 'csp_cta_heading', 'options', 'Want a result like <span>this</span>?' ) ); ?></h2>
						<p class="sub-heading"><?php echo esc_html( get_field( 'csp_cta_subheading', 'options' ) ?: 'Tell us the result you are after and we will reply within one working day with a clear next step.' ); ?></p>
					</div>
					<div class="cta-actions">
						<a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a>
						<a class="button-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'case_study' ) ); ?>">All Case Studies</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	// CreativeWork node attached to Yoast's Organization (@id /#organization),
	// the work-single pattern; `about` welds it to the documented project's
	// own node when the pairing resolves.
	$cs_schema = [
		'@context'    => 'https://schema.org',
		'@type'       => 'CreativeWork',
		'@id'         => get_permalink() . '#casestudy',
		'name'        => $cs_client,
		'description' => $cs_summary,
		'url'         => get_permalink(),
		'provider'    => [ '@id' => home_url( '/#organization' ) ],
	];
	if ( $cs_image ) {
		$cs_schema['image'] = $cs_image['url'];
	}
	if ( $cs_year ) {
		$cs_schema['dateCreated'] = $cs_year;
	}
	if ( $cs_terms ) {
		$cs_schema['keywords'] = implode( ', ', wp_list_pluck( $cs_terms, 'name' ) );
	}
	if ( $cs_project_url ) {
		$cs_schema['about'] = [ '@id' => $cs_project_url . '#creativework' ];
	}
	echo '<script type="application/ld+json">'
		. wp_json_encode( $cs_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>';

endwhile;

get_footer();
