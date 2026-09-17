<?php
/**
 * The testimonial spotlight: one review at a time, with autoplay progress, a
 * counter and arrows (common/_testimonial-spotlight.scss + homepage/testimonials.js,
 * which binds the single #testimonial-splide on a page).
 *
 * Args:
 *   items    array[] from vc_testimonial_items() / vc_testimonial_item(). Omit to read the CPT.
 *   count    int     how many CPT reviews to read when no items are passed (default 6).
 *   carousel bool    false renders the same anatomy as a static stack with no
 *                    Splide hooks: a page's second spotlight, or a single quote.
 *
 * A review with no saved photo shows no portrait. When none of the reviews has
 * one the panel goes and the slide becomes an attribution / quote split; when
 * only some do, the panel carries a monogram for the rest. Each quote sets its
 * own type size from its length (--q), so a short review fills the same space
 * as a long one instead of floating above a gap.
 */
$items = $args['items'] ?? vc_testimonial_items( (int) ( $args['count'] ?? 6 ) );
$items = array_values( array_filter( (array) $items, function ( $item ) {
	return ! empty( $item['quote'] );
} ) );
if ( ! $items ) {
	return;
}

$count      = count( $items );
$carousel   = ( $args['carousel'] ?? true ) && $count > 0;
$has_photos = (bool) array_filter( array_column( $items, 'photo_id' ) );

$classes = [ 'testimonial-spotlight', $has_photos ? 'has-photos' : 'no-photos' ];
if ( $carousel ) {
	array_unshift( $classes, 'splide' );
} else {
	$classes[] = 'spotlight-static';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $carousel ? ' id="testimonial-splide"' : ''; ?> aria-label="Client testimonials">
	<div class="spotlight-layout">
		<?php if ( $has_photos ) : ?>
			<div class="spotlight-photo" aria-hidden="true">
				<?php foreach ( $items as $i => $item ) :
					$active = 0 === $i ? ' is-active' : '';
					if ( $item['photo_id'] ) {
						echo wp_get_attachment_image( $item['photo_id'], 'large', false, [
							'class'   => 'spotlight-portrait' . $active,
							'alt'     => '',
							'loading' => 'lazy',
							'sizes'   => '400px',
						] );
					} else { ?>
						<span class="spotlight-portrait spotlight-monogram<?php echo esc_attr( $active ); ?>"><?php echo esc_html( vc_initials( $item['name'] ) ); ?></span>
					<?php }
				endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="spotlight-main">
			<div class="spotlight-mark" aria-hidden="true">“</div>
			<div class="<?php echo $carousel ? 'splide__track' : 'spotlight-stack'; ?>">
				<ul class="<?php echo $carousel ? 'splide__list' : 'spotlight-stack-list'; ?>">
					<?php foreach ( $items as $item ) : ?>
						<li class="<?php echo $carousel ? 'splide__slide' : 'spotlight-stack-item'; ?>">
							<figure class="spotlight-figure">
								<blockquote>
									<p class="spotlight-quote" style="--q: <?php echo esc_attr( vc_quote_scale( $item['quote'] ) ); ?>"><?php echo esc_html( $item['quote'] ); ?></p>
								</blockquote>
								<figcaption class="spotlight-who">
									<?php if ( $item['photo_id'] ) : ?>
										<span class="cite-avatar" aria-hidden="true">
											<?php echo wp_get_attachment_image( $item['photo_id'], 'thumbnail', false, [ 'alt' => '', 'loading' => 'lazy', 'sizes' => '52px' ] ); ?>
										</span>
									<?php endif; ?>
									<span class="cite-text">
										<span class="t-name"><?php echo esc_html( $item['name'] ); ?></span>
										<?php if ( $item['company'] ) : ?>
											<span class="t-company"><?php echo esc_html( $item['company'] ); ?></span>
										<?php endif; ?>
									</span>
								</figcaption>
							</figure>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php if ( $carousel && $count > 1 ) : ?>
				<div class="spotlight-footer">
					<div class="spotlight-progress" aria-hidden="true"><div class="spotlight-progress-bar"></div></div>
					<div class="spotlight-controls">
						<span class="spotlight-counter" aria-hidden="true"><span class="current">01</span> / <span class="total"><?php echo esc_html( str_pad( $count, 2, '0', STR_PAD_LEFT ) ); ?></span></span>
						<div class="splide__arrows">
							<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous testimonial">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
							</button>
							<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next testimonial">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
