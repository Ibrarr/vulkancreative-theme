<?php
/**
 * Block: testimonials. Reuses the shared spotlight (common/_testimonial-spotlight
 * .scss + homepage/testimonials.js, which binds a single #testimonial-splide).
 * Only the first testimonials block on a page carries that id + carousel; a
 * second renders as a static list.
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$items   = $args['items'] ?? [];
$carousel = ! empty( $args['allow_carousel'] );
if ( empty( $items ) ) { return; }
$count = count( $items );
?>
<section class="lp-proof is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<div class="content">
			<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
		</div>
		<div class="splide testimonial-spotlight<?php echo $carousel ? '' : ' spotlight-static'; ?>"<?php echo $carousel ? ' id="testimonial-splide"' : ''; ?> aria-label="Client testimonials">
			<div class="spotlight-layout">
				<div class="spotlight-photo" aria-hidden="true">
					<?php foreach ( $items as $ti => $item ) : ?>
						<img class="spotlight-portrait<?php echo 0 === $ti ? ' is-active' : ''; ?>" loading="lazy" src="<?php echo esc_url( $item['photo'] ); ?>" alt="">
					<?php endforeach; ?>
				</div>
				<div class="spotlight-main">
					<div class="spotlight-mark" aria-hidden="true">“</div>
					<div class="splide__track">
						<ul class="splide__list">
							<?php foreach ( $items as $item ) : ?>
								<li class="splide__slide">
									<blockquote>
										<p class="spotlight-quote"><?php echo esc_html( $item['quote'] ); ?></p>
										<cite>
											<span class="cite-avatar" aria-hidden="true">
												<img loading="lazy" src="<?php echo esc_url( $item['photo'] ); ?>" alt="">
											</span>
											<span class="cite-text">
												<span class="t-name"><?php echo esc_html( $item['name'] ); ?></span>
												<span class="t-company"><?php echo esc_html( $item['company'] ); ?></span>
											</span>
										</cite>
									</blockquote>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php if ( $carousel && $count > 1 ) : ?>
						<div class="spotlight-footer">
							<div class="spotlight-progress" aria-hidden="true"><div class="spotlight-progress-bar"></div></div>
							<div class="spotlight-controls">
								<span class="spotlight-counter" aria-hidden="true"><span class="current">01</span> / <span class="total"><?php echo esc_html( str_pad( $count, 2, '0', STR_PAD_LEFT ) ); ?></span></span>
								<div class="splide__arrows">
									<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous testimonial">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
									</button>
									<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next testimonial">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
									</button>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
