<?php
/**
 * Single project showcase at /work/{slug}/ (project post type). A project-led
 * hero on the dark forge band (breadcrumbs, client name, meta row, live-site
 * link, large media panel), then: overview (statement, fact ledger,
 * deliverables), editorial gallery, the related-work wheel (projects sharing
 * a service with this one) and the closing CTA band (no form: the spokes
 * rule; footer.php hides the footer CTA here). Optional fields hide their
 * sections, so lighter projects render
 * fewer sections on the same template. Shared strings live on the Work Page
 * Settings options page (wk_ fields); per-project content is the pj_ fields.
 */

get_header();

while ( have_posts() ) :
	the_post();

	$pj_id          = get_the_ID();
	$pj_client      = get_field( 'pj_client_name' ) ?: get_the_title();
	$pj_sector      = get_field( 'pj_sector' );
	$pj_description = get_field( 'pj_description' );
	$pj_image       = get_field( 'pj_image' );
	$pj_link        = get_field( 'pj_link' );
	$pj_year        = get_field( 'pj_year' );
	$pj_statement   = get_field( 'pj_overview_statement' );
	$pj_support     = get_field( 'pj_overview_support' );
	$pj_gallery     = get_field( 'pj_gallery' ) ?: [];
	$pj_case_study  = (int) get_field( 'pj_case_study' );

	$pj_terms = get_the_terms( $pj_id, 'service' );
	$pj_terms = ( $pj_terms && ! is_wp_error( $pj_terms ) ) ? $pj_terms : [];

	// The case-study link: live since July 2026 (the case-studies build). It
	// feeds the hero ghost button and the full-story band before the related
	// wheel; both render only when the linked case study is public.
	$pj_case_study_url = '';
	if ( $pj_case_study && is_post_type_viewable( 'case_study' ) && 'publish' === get_post_status( $pj_case_study ) ) {
		$pj_case_study_url = get_permalink( $pj_case_study );
	}

	// Alternating light surfaces below the hero, the service-page closure:
	// conditional sections simply hand their slot down.
	$pj_surface_i = 0;
	$pj_surface   = function () use ( &$pj_surface_i ) {
		return ( $pj_surface_i++ % 2 === 0 ) ? 'surface-white' : 'surface-grey';
	};
	?>

	<section class="page-hero project-hero" id="top">
		<div class="page-hero-glow" aria-hidden="true"></div>
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-9 content">
					<div class="breadcrumbs"><?php echo do_shortcode( '[wpseo_breadcrumb]' ); ?></div>
					<h1><?php echo esc_html( $pj_client ); ?></h1>
					<?php if ( $pj_sector || $pj_year || $pj_terms ) : ?>
						<ul class="hero-meta">
							<?php if ( $pj_sector ) : ?><li><?php echo esc_html( $pj_sector ); ?></li><?php endif; ?>
							<?php if ( $pj_year ) : ?><li><?php echo esc_html( $pj_year ); ?></li><?php endif; ?>
							<?php if ( $pj_terms ) : ?>
								<li class="hero-meta-services">
									<?php foreach ( $pj_terms as $meta_i => $meta_term ) :
										$meta_link = get_term_link( $meta_term );
										if ( is_wp_error( $meta_link ) ) {
											continue;
										}
										?><a href="<?php echo esc_url( $meta_link ); ?>"><?php echo esc_html( $meta_term->name ); ?></a><?php echo $meta_i < count( $pj_terms ) - 1 ? '<span aria-hidden="true">, </span>' : ''; ?><?php
									endforeach; ?>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $pj_link || $pj_case_study_url ) : ?>
						<div class="hero-actions">
							<?php if ( $pj_case_study_url ) : ?>
								<a class="button-ghost" href="<?php echo esc_url( $pj_case_study_url ); ?>">Read the Case Study</a>
							<?php endif; ?>
							<?php if ( $pj_link ) : ?>
								<a class="button-ghost hero-live-link" href="<?php echo esc_url( $pj_link ); ?>" target="_blank" rel="noopener">View Live Site<svg class="live-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg><span class="visually-hidden">(opens in a new tab)</span></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $pj_image ) : ?>
				<div class="hero-media">
					<?php echo wp_get_attachment_image( $pj_image['ID'], 'header-image', false, [
						'class'         => 'hero-media-img',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					] ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="project-overview <?php echo esc_attr( $pj_surface() ); ?>">
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-6">
					<div class="content">
						<h2><?php echo wp_kses_post( vc_heading_parts( 'wk_overview_heading', 'options', 'The <span>work</span>.' ) ); ?></h2>
					</div>
					<?php if ( $pj_statement || $pj_support || $pj_description ) : ?>
						<div class="overview-lead">
							<p class="overview-statement"><?php echo esc_html( $pj_statement ?: $pj_description ); ?></p>
							<?php if ( $pj_support ) : ?>
								<p class="overview-support"><?php echo esc_html( $pj_support ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<dl class="fact-ledger">
						<div class="fact-row">
							<dt>Client</dt>
							<dd><?php echo esc_html( $pj_client ); ?></dd>
						</div>
						<?php if ( $pj_sector ) : ?>
							<div class="fact-row">
								<dt>Sector</dt>
								<dd><?php echo esc_html( $pj_sector ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $pj_year ) : ?>
							<div class="fact-row">
								<dt>Year</dt>
								<dd><?php echo esc_html( $pj_year ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $pj_terms ) : ?>
							<div class="fact-row">
								<dt>Services</dt>
								<dd>
									<?php foreach ( $pj_terms as $fact_i => $fact_term ) :
										$fact_link = get_term_link( $fact_term );
										if ( is_wp_error( $fact_link ) ) {
											continue;
										}
										?><a href="<?php echo esc_url( $fact_link ); ?>"><?php echo esc_html( $fact_term->name ); ?></a><?php echo $fact_i < count( $pj_terms ) - 1 ? '<span aria-hidden="true">, </span>' : ''; ?><?php
									endforeach; ?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $pj_link ) : ?>
							<div class="fact-row">
								<dt>Live site</dt>
								<dd><a class="fact-live-link" href="<?php echo esc_url( $pj_link ); ?>" target="_blank" rel="noopener"><?php echo esc_html( wp_parse_url( $pj_link, PHP_URL_HOST ) ?: $pj_link ); ?><svg class="live-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg><span class="visually-hidden">(opens in a new tab)</span></a></dd>
							</div>
						<?php endif; ?>
					</dl>
				</div>
			</div>
			<?php if ( have_rows( 'pj_deliverables' ) ) : ?>
				<div class="deliverables">
					<p class="deliverables-label">What we delivered</p>
					<ul class="deliverables-list">
						<?php while ( have_rows( 'pj_deliverables' ) ) : the_row(); ?>
							<li><?php echo esc_html( get_sub_field( 'label' ) ); ?></li>
						<?php endwhile; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( ! empty( $pj_gallery ) ) : ?>
		<section class="project-gallery <?php echo esc_attr( $pj_surface() ); ?>">
			<div class="container px-4">
				<h2 class="visually-hidden">Project gallery</h2>
				<div class="row g-4 gallery-grid">
					<?php foreach ( $pj_gallery as $gallery_i => $gallery_row ) :
						if ( empty( $gallery_row['image'] ) ) {
							continue;
						}
						$gallery_image   = $gallery_row['image'];
						$gallery_caption = $gallery_row['caption'] ?? '';
						// Rhythm per group of three: a full-width panel, then a
						// 7/5 pair (the archive grid's editorial family).
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
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $pj_case_study_url ) :
		$band_value   = get_field( 'cs_metric_value', $pj_case_study );
		$band_label   = get_field( 'cs_metric_label', $pj_case_study );
		$band_summary = get_field( 'cs_summary', $pj_case_study );
		?>
		<?php // The full story: the linked case study's headline result as a band-wide link. ?>
		<section class="project-case-study-band <?php echo esc_attr( $pj_surface() ); ?>">
			<div class="container px-4">
				<a class="band-inner" href="<?php echo esc_url( $pj_case_study_url ); ?>">
					<?php if ( $band_value ) : ?>
						<span class="band-metric">
							<span class="band-metric-value"><?php echo esc_html( $band_value ); ?></span>
							<?php if ( $band_label ) : ?><span class="band-metric-label"><?php echo esc_html( $band_label ); ?></span><?php endif; ?>
						</span>
					<?php endif; ?>
					<span class="band-text">
						<span class="band-eyebrow">The full story</span>
						<?php if ( $band_summary ) : ?><span class="band-line"><?php echo esc_html( $band_summary ); ?></span><?php endif; ?>
					</span>
					<span class="band-action"><span class="band-action-label">Read the Case Study</span><svg class="band-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span>
				</a>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Related work: projects sharing any of this project's services, newest
	// first, on the shared wheel; tiles link to the projects' own pages.
	$related_items = [];
	if ( $pj_terms ) {
		$related_query = new WP_Query( [
			'post_type'      => 'project',
			'posts_per_page' => 8,
			'post__not_in'   => [ $pj_id ],
			'no_found_rows'  => true,
			'tax_query'      => [ [ 'taxonomy' => 'service', 'field' => 'term_id', 'terms' => wp_list_pluck( $pj_terms, 'term_id' ) ] ],
		] );
		if ( $related_query->have_posts() ) {
			while ( $related_query->have_posts() ) {
				$related_query->the_post();
				$related_image = get_field( 'pj_image' );
				if ( ! $related_image ) {
					continue;
				}

				// Plate label: Yoast's primary term wins, then the first term
				// (mixed services here, unlike the service pages).
				$related_service = '';
				if ( function_exists( 'yoast_get_primary_term_id' ) ) {
					$related_primary_id = yoast_get_primary_term_id( 'service', get_the_ID() );
					if ( $related_primary_id ) {
						$related_primary = get_term( $related_primary_id, 'service' );
						if ( $related_primary && ! is_wp_error( $related_primary ) ) {
							$related_service = $related_primary->name;
						}
					}
				}
				if ( ! $related_service ) {
					$related_terms = get_the_terms( get_the_ID(), 'service' );
					if ( $related_terms && ! is_wp_error( $related_terms ) ) {
						$related_service = $related_terms[0]->name;
					}
				}

				$related_items[] = [
					'client'      => get_field( 'pj_client_name' ) ?: get_the_title(),
					'sector'      => get_field( 'pj_sector' ),
					'description' => get_field( 'pj_description' ),
					'image'       => $related_image,
					'link'        => get_permalink(),
					'service'     => $related_service,
				];
			}
			wp_reset_postdata();
		}
	}
	?>
	<?php if ( ! empty( $related_items ) ) : ?>
		<section class="project-related <?php echo esc_attr( $pj_surface() ); ?>">
			<div class="container px-4">
				<?php
				get_template_part( 'template-parts/work-wheel', null, [
					'projects'    => $related_items,
					'heading'     => vc_heading_parts( 'wk_related_heading', 'options', 'Related <span>work</span>.' ),
					'stage_label' => 'Related projects',
				] );
				?>
			</div>
		</section>
	<?php endif; ?>

	<section class="project-cta <?php echo esc_attr( $pj_surface() ); ?>" id="enquire">
		<?php // Oversized outlined client name as the closing brand moment (the service-cta wordmark recipe). ?>
		<span class="cta-wordmark" aria-hidden="true"><?php echo esc_html( $pj_client ); ?></span>
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-8">
					<div class="content">
						<h2><?php echo wp_kses_post( vc_heading_parts( 'wk_cta_heading', 'options', 'Got a project like <span>this</span>?' ) ); ?></h2>
						<p class="sub-heading"><?php echo esc_html( get_field( 'wk_cta_subheading', 'options' ) ?: 'Tell us where you want to be and we will reply within one working day with a clear next step.' ); ?></p>
					</div>
					<div class="cta-actions">
						<a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a>
						<a class="button-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Back to Our Work</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	// CreativeWork node attached to Yoast's Organization (@id /#organization),
	// the service-page pattern.
	$pj_schema = [
		'@context'    => 'https://schema.org',
		'@type'       => 'CreativeWork',
		'@id'         => get_permalink() . '#creativework',
		'name'        => $pj_client,
		'description' => $pj_description,
		'url'         => get_permalink(),
		'provider'    => [ '@id' => home_url( '/#organization' ) ],
	];
	if ( $pj_image ) {
		$pj_schema['image'] = $pj_image['url'];
	}
	if ( $pj_year ) {
		$pj_schema['dateCreated'] = $pj_year;
	}
	if ( $pj_terms ) {
		$pj_schema['keywords'] = implode( ', ', wp_list_pluck( $pj_terms, 'name' ) );
	}
	echo '<script type="application/ld+json">'
		. wp_json_encode( $pj_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>';

endwhile;

get_footer();
