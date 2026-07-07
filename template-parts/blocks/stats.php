<?php
/**
 * Block: stats / results. Reuses the shared .results component (its own #FFF /
 * #1E1E1E band) and homepage/counter.js (bound to .results .stat-number).
 */
$heading = $args['heading'] ?? '';
$sub     = $args['subheading'] ?? '';
$stats   = $args['stats'] ?? [];
if ( empty( $stats ) ) { return; }
?>
<section class="results lp-stats">
	<div class="container px-4">
		<div class="content">
			<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
			<?php if ( $sub ) : ?>
				<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $sub ); ?></p>
			<?php endif; ?>
		</div>
		<div class="row gx-4 gy-4 stats-grid" data-reveal="stagger">
			<?php foreach ( $stats as $stat ) : ?>
				<div class="col-lg-3 col-6">
					<div class="stat">
						<span class="stat-number"><?php echo esc_html( $stat['prefix'] . $stat['number'] . $stat['suffix'] ); ?></span>
						<p class="stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
