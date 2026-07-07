<?php
/**
 * Block: hero. Dark anchor band in both modes. Optional global-logo marquee
 * scrolls across the bottom when the "Show logo marquee" toggle is on.
 */
$heading = $args['heading'] ?? '';
$sub     = $args['subheading'] ?? '';
$pl      = $args['primary_label'] ?? '';
$pt      = $args['primary_target'] ?? '#lp-cta';
$sl      = $args['secondary_label'] ?? '';
$st      = $args['secondary_target'] ?? '';
$note    = $args['note'] ?? '';
$mark    = $args['mark_text'] ?? '';

$logos = [];
if ( ! empty( $args['show_logos'] ) && have_rows( 'worked_with_logos', 'options' ) ) {
	while ( have_rows( 'worked_with_logos', 'options' ) ) { the_row();
		$img = get_sub_field( 'logo' );
		if ( $img ) { $logos[] = $img; }
	}
}
?>
<section class="lp-hero<?php echo $logos ? ' has-marquee' : ''; ?>" id="lp-top">
	<div class="lp-hero-glow" aria-hidden="true"></div>
	<?php if ( $mark ) : ?>
		<div class="lp-hero-mark" aria-hidden="true"><?php echo esc_html( $mark ); ?></div>
	<?php endif; ?>
	<div class="lp-hero-inner">
		<div class="container px-4">
			<div class="row">
				<div class="col-lg-9 content">
					<h1 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h1>
					<?php if ( $sub ) : ?>
						<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $sub ); ?></p>
					<?php endif; ?>
					<?php if ( $pl || $sl ) : ?>
						<div class="hero-actions" data-reveal="fade">
							<?php if ( $pl ) : ?>
								<a class="button" href="<?php echo esc_attr( $pt ); ?>"><?php echo esc_html( $pl ); ?></a>
							<?php endif; ?>
							<?php if ( $sl ) : ?>
								<a class="button-ghost" href="<?php echo esc_attr( $st ?: '#lp-cta' ); ?>"><?php echo esc_html( $sl ); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $note ) : ?>
						<p class="hero-note" data-reveal="fade"><?php echo esc_html( $note ); ?></p>
						<span class="hero-rule" data-reveal="rule" aria-hidden="true"></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php if ( $logos ) : ?>
		<div class="lp-hero-marquee">
			<div class="splide lp-logo-splide" aria-label="Companies we have worked with">
				<div class="splide__track">
					<ul class="splide__list">
						<?php foreach ( $logos as $img ) : ?>
							<li class="splide__slide">
								<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ?: $img['title'] ); ?>" loading="lazy">
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>
