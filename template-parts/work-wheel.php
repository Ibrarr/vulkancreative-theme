<?php
/**
 * The work wheel: the GSAP arc carousel of project tiles, shared by the
 * homepage Our Work section and the service pages' recent work section.
 * Styles live in common/_work-wheel.scss and the behaviour in
 * homepage/our-work.js (bound by the #work-wheel id, shipped in both
 * bundles). Callers prepare the project arrays and pass them in:
 *
 * get_template_part( 'template-parts/work-wheel', null, [
 *     'projects'    => [ [ 'client', 'sector', 'description', 'image' (ACF array), 'link', 'service' ], ... ],
 *     'heading'     => 'Section <span>heading</span>',   // HTML, spans allowed
 *     'subheading'  => 'Optional sub-line',              // plain text, optional
 *     'stage_label' => 'Selected projects',              // carousel aria-label
 * ] );
 */

$wheel_projects   = $args['projects'] ?? [];
$wheel_heading    = $args['heading'] ?? '';
$wheel_subheading = $args['subheading'] ?? '';
$wheel_label      = $args['stage_label'] ?? 'Selected projects';

// Wheel order: the first pick takes the centre slot and later picks
// alternate right then left around it, so the caller's order radiates
// outwards from the middle of the wheel.
$wheel_slots = [];
foreach ( $wheel_projects as $pick_i => $wheel_project ) {
	$slot = ( $pick_i % 2 ) ? intdiv( $pick_i, 2 ) + 1 : -intdiv( $pick_i, 2 );
	$wheel_slots[ $slot ] = $wheel_project;
}
ksort( $wheel_slots );
$wheel_projects = array_values( $wheel_slots );
?>
<div class="work-wheel" id="work-wheel">
	<div class="shelf-head">
		<div class="content">
			<h2><?php echo wp_kses_post( $wheel_heading ); ?></h2>
			<?php if ( $wheel_subheading ) : ?>
				<p class="sub-heading"><?php echo esc_html( $wheel_subheading ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( count( $wheel_projects ) > 1 ) : ?>
			<div class="wheel-controls">
				<p class="wheel-hint" aria-hidden="true">Drag to spin</p>
				<div class="wheel-arrows">
					<button class="wheel-arrow wheel-arrow--prev" type="button" aria-label="Previous projects" disabled>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
					</button>
					<button class="wheel-arrow wheel-arrow--next" type="button" aria-label="Next projects">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( ! empty( $wheel_projects ) ) : ?>
		<div class="wheel-stage" role="group" aria-roledescription="carousel" aria-label="<?php echo esc_attr( $wheel_label ); ?>">
			<ul class="wheel-track">
				<?php foreach ( $wheel_projects as $wheel_project ) :
					// Internal links (the projects' own pages, the default
					// since July 2026) navigate in-tab with the internal
					// arrow; external ones keep the ↗ and a new tab.
					$tile_link     = $wheel_project['link'];
					$tile_internal = $tile_link && 0 === strpos( $tile_link, home_url() );
					$tile_tag      = $tile_link ? 'a' : 'div';
					$tile_attrs    = '';
					if ( $tile_link ) {
						$tile_attrs = ' href="' . esc_url( $tile_link ) . '"' . ( $tile_internal ? '' : ' target="_blank" rel="noopener"' );
					}
					// Keep the arrow glued to the last word of the name.
					$client_words = explode( ' ', $wheel_project['client'] );
					$client_last  = array_pop( $client_words );
					$client_head  = implode( ' ', $client_words );
					?>
					<li class="wheel-card">
						<<?php echo $tile_tag; ?> class="tile-media"<?php echo $tile_attrs; ?>>
							<img class="tile-img" loading="lazy" src="<?php echo esc_url( $wheel_project['image']['sizes']['large'] ?? $wheel_project['image']['url'] ); ?>" alt="">
							<span class="tile-scrim" aria-hidden="true"></span>
							<?php if ( $wheel_project['service'] ) : ?><span class="tile-service"><?php echo esc_html( $wheel_project['service'] ); ?></span><?php endif; ?>
							<span class="tile-caption">
								<?php if ( $wheel_project['sector'] ) : ?><span class="tile-sector"><?php echo esc_html( $wheel_project['sector'] ); ?></span><?php endif; ?>
								<?php if ( $tile_internal ) : ?>
									<h3 class="tile-client"><?php echo $client_head ? esc_html( $client_head ) . ' ' : ''; ?><span class="tile-client-end"><?php echo esc_html( $client_last ); ?><svg class="tile-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span></h3>
								<?php elseif ( $tile_link ) : ?>
									<h3 class="tile-client"><?php echo $client_head ? esc_html( $client_head ) . ' ' : ''; ?><span class="tile-client-end"><?php echo esc_html( $client_last ); ?><svg class="tile-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg><span class="visually-hidden">(opens the live site in a new tab)</span></span></h3>
								<?php else : ?>
									<h3 class="tile-client"><?php echo esc_html( $wheel_project['client'] ); ?></h3>
								<?php endif; ?>
								<?php if ( $wheel_project['description'] ) : ?><span class="tile-line"><?php echo esc_html( $wheel_project['description'] ); ?></span><?php endif; ?>
							</span>
						</<?php echo $tile_tag; ?>>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>
