<?php
/**
 * Block: testimonials. Renders the shared spotlight partial. Only the first
 * testimonials block on a page carries the carousel (testimonials.js binds a
 * single #testimonial-splide); a second renders as a static stack.
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$items   = $args['items'] ?? [];
$carousel = ! empty( $args['allow_carousel'] );
if ( empty( $items ) ) { return; }
?>
<section class="lp-proof is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<div class="content">
			<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
		</div>
		<?php get_template_part( 'template-parts/testimonial-spotlight', null, [ 'items' => $items, 'carousel' => $carousel ] ); ?>
	</div>
</section>
