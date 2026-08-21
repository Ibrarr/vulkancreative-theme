<?php
// The aggregate rating chip beside a section heading (homepage testimonials,
// About proof, services hub proof), reading the single Global Settings source
// via vc_google_reviews(). Google-backed when a review count and profile link
// are saved: the whole chip links out, the full-colour G sits on the label
// line and the wording is the compact "5.0 · N Google Reviews" (Ibrar's call,
// Aug 2026), with the stars kept on their own row away from the G and an
// as-of date beside the overall figure (Google's guideline). Without count or
// link it falls back to the neutral unlinked presentation.
$gr        = vc_google_reviews();
$google_ok = $gr['count'] > 0 && $gr['url'];
$star_svg  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 2l2.92 6.26 6.83.62-5.17 4.56 1.54 6.7L12 16.67 5.88 20.14l1.54-6.7L2.25 8.88l6.83-.62L12 2z"/></svg>';
?>
<?php if ( $google_ok ) :
	$reviews_word = 1 === $gr['count'] ? 'Google Review' : 'Google Reviews';
	$chip_label   = $gr['rating'] . ' rating, ' . $gr['count'] . ' ' . $reviews_word . ( $gr['as_of'] ? ', as of ' . $gr['as_of'] : '' ) . '. Opens our Google profile in a new tab.';
	?>
	<a class="rating-chip" href="<?php echo esc_url( $gr['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $chip_label ); ?>">
		<span class="rating-stars" aria-hidden="true"><?php echo str_repeat( $star_svg, 5 ); ?></span>
		<span class="rating-line" aria-hidden="true">
			<img class="rating-google-mark" src="<?php echo esc_url( VC_TEMPLATE_URI . '/assets/images/logos/google-g.webp' ); ?>" alt="" width="20" height="20">
			<span class="rating-text"><span class="rating-value"><?php echo esc_html( $gr['rating'] ); ?></span><span class="rating-dot">·</span><?php echo (int) $gr['count']; ?> <?php echo esc_html( $reviews_word ); ?></span>
		</span>
		<?php if ( $gr['as_of'] ) : ?>
			<span class="rating-asof" aria-hidden="true">as of <?php echo esc_html( $gr['as_of'] ); ?></span>
		<?php endif; ?>
	</a>
<?php else : ?>
	<div class="rating-chip">
		<span class="rating-stars" aria-hidden="true"><?php echo str_repeat( $star_svg, 5 ); ?></span>
		<span class="rating-text"><span class="rating-value"><?php echo esc_html( $gr['rating'] ); ?></span> average client rating</span>
	</div>
<?php endif; ?>
