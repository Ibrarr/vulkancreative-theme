<?php
/**
 * Shared Insights card.
 *
 * One card design for the blog index, category and author archives, search
 * results and the single-post "More insights" row. Runs inside any post loop,
 * so it reads from the current post via get_the_*() and needs no arguments.
 *
 * The card body is a single link to the post; the category plate is a separate
 * link to its category archive (a sibling, not nested, so there are two clean
 * tab stops with distinct destinations). The image is decorative (alt="")
 * because the title announces the post.
 */

// Primary category: Yoast's pick, else the first assigned category.
$card_cat = null;

if ( class_exists( 'WPSEO_Primary_Term' ) ) {
	$wpseo_primary    = new WPSEO_Primary_Term( 'category', get_the_ID() );
	$wpseo_primary_id = $wpseo_primary->get_primary_term();

	if ( $wpseo_primary_id && ! is_wp_error( $wpseo_primary_id ) ) {
		$maybe_term = get_term( $wpseo_primary_id );
		if ( $maybe_term && ! is_wp_error( $maybe_term ) ) {
			$card_cat = $maybe_term;
		}
	}
}

if ( ! $card_cat ) {
	$card_cats = get_the_category();
	if ( ! empty( $card_cats ) ) {
		$card_cat = $card_cats[0];
	}
}

// Resolve the category archive URL once (empty if it errors).
$card_cat_url = '';
if ( $card_cat ) {
	$maybe_cat_link = get_term_link( $card_cat );
	if ( ! is_wp_error( $maybe_cat_link ) ) {
		$card_cat_url = $maybe_cat_link;
	}
}

// Read time from the ACF content field; hidden when there is no content.
$card_words     = str_word_count( strip_tags( (string) get_field( 'content' ) ) );
$card_read_time = $card_words ? max( 1, (int) ceil( $card_words / 200 ) ) : 0;
?>

<article <?php post_class( 'insight-card col-lg-4 col-md-6 col-12' ); ?>>
	<a class="insight-card-link" href="<?php the_permalink(); ?>">
		<span class="insight-card-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php
				the_post_thumbnail( 'large', [
					'class'   => 'insight-card-img',
					'loading' => 'lazy',
					'alt'     => '',
				] );
				?>
			<?php else : ?>
				<span class="insight-card-media-empty" aria-hidden="true"></span>
			<?php endif; ?>

			<span class="insight-card-scrim" aria-hidden="true"></span>
		</span>

		<span class="insight-card-body">
			<h3 class="insight-card-title"><?php the_title(); ?></h3>

			<p class="insight-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>

			<span class="insight-card-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
				<?php if ( $card_read_time ) : ?>
					<span class="insight-card-dot" aria-hidden="true">·</span>
					<span class="insight-card-readtime"><?php echo esc_html( $card_read_time ); ?> min read</span>
				<?php endif; ?>
			</span>
		</span>
	</a>

	<?php if ( $card_cat && $card_cat_url ) : ?>
		<a class="insight-card-cat" href="<?php echo esc_url( $card_cat_url ); ?>" aria-label="<?php echo esc_attr( $card_cat->name ); ?> category"><?php echo esc_html( $card_cat->name ); ?></a>
	<?php elseif ( $card_cat ) : ?>
		<span class="insight-card-cat"><?php echo esc_html( $card_cat->name ); ?></span>
	<?php endif; ?>
</article>
