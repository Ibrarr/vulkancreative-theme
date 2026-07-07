<?php
/**
 * Block: steps / how it works. Numbered rail with scroll-scrubbed ember line.
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$sub     = $args['subheading'] ?? '';
$steps   = $args['steps'] ?? [];
if ( empty( $steps ) ) { return; }
?>
<section class="lp-steps is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<div class="row lp-steps-head lp-split-head">
			<div class="col-lg-7">
				<div class="content">
					<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
				</div>
			</div>
			<?php if ( $sub ) : ?>
				<div class="col-lg-4 offset-lg-1 lp-steps-intro">
					<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $sub ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<div class="lp-steps-rail-wrap">
			<span class="lp-steps-rail" aria-hidden="true"></span>
			<ol class="lp-steps-list" data-reveal="stagger" style="--lp-steps-count: <?php echo count( $steps ); ?>;">
				<?php foreach ( $steps as $i => $step ) : ?>
					<li class="lp-step">
						<span class="step-index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3 class="step-title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="step-desc"><?php echo esc_html( $step['description'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
