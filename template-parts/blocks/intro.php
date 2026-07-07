<?php
/**
 * Block: intro / rich text. Full-width heading (so the big display type never
 * clips in a narrow column), then the body paragraphs distributed across two
 * columns — the first half left, the rest right (a single paragraph reads as
 * one lead column). Stacks below lg.
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$body    = $args['body'] ?? '';

// Split the WYSIWYG body into whole paragraphs so columns break cleanly.
$paras = [];
if ( $body && preg_match_all( '/<p\b[^>]*>.*?<\/p>/is', $body, $m ) ) {
	$paras = $m[0];
}
$count    = count( $paras );
$two_col  = $count >= 2;
$half     = (int) ceil( $count / 2 );
$left_html  = $two_col ? implode( '', array_slice( $paras, 0, $half ) ) : $body;
$right_html = $two_col ? implode( '', array_slice( $paras, $half ) ) : '';
?>
<section class="lp-intro is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<?php if ( $heading ) : ?>
			<div class="content lp-intro-head">
				<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
			</div>
		<?php endif; ?>
		<?php if ( $body ) : ?>
			<?php if ( $two_col ) : ?>
				<div class="row lp-intro-cols">
					<div class="col-lg-6 lp-intro-body" data-reveal="fade"><?php echo wp_kses_post( $left_html ); ?></div>
					<div class="col-lg-6 lp-intro-body" data-reveal="fade"><?php echo wp_kses_post( $right_html ); ?></div>
				</div>
			<?php else : ?>
				<div class="lp-intro-body lp-intro-body--single" data-reveal="fade"><?php echo wp_kses_post( $left_html ); ?></div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
