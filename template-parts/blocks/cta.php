<?php
/**
 * Block: CTA / form. Dark anchor band in both modes. Form mode renders the
 * editor-selectable Gravity Form (vc_page_form picker, default 2); Buttons mode
 * renders a forge button. The last CTA on the page carries the #lp-cta anchor.
 */
$heading   = $args['heading'] ?? '';
$sub       = $args['subheading'] ?? '';
$mode      = $args['mode'] ?? 'form';
$note      = $args['note'] ?? '';
$pl        = $args['primary_label'] ?? '';
$pt        = $args['primary_target'] ?? '';
$is_anchor = ! empty( $args['is_anchor'] );
?>
<section class="lp-cta"<?php echo $is_anchor ? ' id="lp-cta"' : ''; ?>>
	<div class="lp-cta-glow" aria-hidden="true"></div>
	<div class="container px-4">
		<?php if ( 'buttons' === $mode ) : ?>
			<div class="row">
				<div class="col-lg-9">
					<div class="content">
						<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
					</div>
					<?php if ( $sub ) : ?>
						<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $sub ); ?></p>
					<?php endif; ?>
					<?php if ( $pl ) : ?>
						<div class="hero-actions" data-reveal="fade">
							<a class="button" href="<?php echo esc_attr( $pt ?: '/contact/' ); ?>"><?php echo esc_html( $pl ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php else : ?>
			<div class="row gx-5">
				<div class="col-lg-7 lp-cta-intro">
					<div class="content">
						<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
					</div>
					<?php if ( $sub ) : ?>
						<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $sub ); ?></p>
					<?php endif; ?>
				</div>
				<div class="col-lg-5 lp-cta-form form">
					<div class="form-container" data-reveal="fade">
						<?php vc_render_form( 10 ); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
