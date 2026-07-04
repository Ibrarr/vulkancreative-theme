<?php
/**
 * Archive work card: one project tile in the /work/ grid, linking to the
 * project's own page. Mirrors the wheel tile's anatomy (depth scrim, primary
 * service plate, caption-anchored gradient, glued arrow) but as an internal
 * link with the internal arrow, and with no saturation hover: that stays
 * grandfathered to the wheel. Styles in
 * components/project/components/_index.scss; assets/js/project/filter.js
 * reads data-services and swaps the column pattern classes when filtering.
 *
 * $args:
 *  - 'project'  Post ID. Required.
 *  - 'pattern'  'wide' (col-lg-7, 16/10 media) or 'narrow' (col-lg-5, 4/3).
 *  - 'hidden'   Server-rendered filter state (true = filtered out).
 */

$card_id = (int) ( $args['project'] ?? 0 );
if ( ! $card_id ) {
	return;
}

$card_image = get_field( 'pj_image', $card_id );
if ( empty( $card_image ) ) {
	return;
}

$card_client      = get_field( 'pj_client_name', $card_id ) ?: get_the_title( $card_id );
$card_sector      = get_field( 'pj_sector', $card_id );
$card_description = get_field( 'pj_description', $card_id );
$card_pattern     = ( $args['pattern'] ?? 'narrow' ) === 'wide' ? 'wide' : 'narrow';
$card_hidden      = ! empty( $args['hidden'] );

// Service label: Yoast's primary term wins, then the first assigned term.
$card_terms   = get_the_terms( $card_id, 'service' );
$card_slugs   = [];
$card_service = '';
if ( $card_terms && ! is_wp_error( $card_terms ) ) {
	$card_slugs   = wp_list_pluck( $card_terms, 'slug' );
	$card_service = $card_terms[0]->name;
	if ( function_exists( 'yoast_get_primary_term_id' ) ) {
		$card_primary_id = yoast_get_primary_term_id( 'service', $card_id );
		if ( $card_primary_id ) {
			$card_primary = get_term( $card_primary_id, 'service' );
			if ( $card_primary && ! is_wp_error( $card_primary ) ) {
				$card_service = $card_primary->name;
			}
		}
	}
}

// Keep the arrow glued to the last word of the name.
$card_words = explode( ' ', $card_client );
$card_last  = array_pop( $card_words );
$card_head  = implode( ' ', $card_words );

$card_classes = 'work-card col-12 col-md-6 ' . ( 'wide' === $card_pattern ? 'col-lg-7 work-card--wide' : 'col-lg-5' );
?>
<article class="<?php echo esc_attr( $card_classes ); ?>" data-services="<?php echo esc_attr( implode( ' ', $card_slugs ) ); ?>"<?php echo $card_hidden ? ' hidden' : ''; ?>>
	<a class="work-card-media" href="<?php echo esc_url( get_permalink( $card_id ) ); ?>">
		<?php echo wp_get_attachment_image( $card_image['ID'], 'large', false, [
			'class'   => 'work-card-img',
			'loading' => 'lazy',
			'alt'     => '',
		] ); ?>
		<span class="work-card-scrim" aria-hidden="true"></span>
		<?php if ( $card_service ) : ?><span class="work-card-plate"><?php echo esc_html( $card_service ); ?></span><?php endif; ?>
		<span class="work-card-caption">
			<?php if ( $card_sector ) : ?><span class="work-card-sector"><?php echo esc_html( $card_sector ); ?></span><?php endif; ?>
			<h3 class="work-card-client"><?php echo $card_head ? esc_html( $card_head ) . ' ' : ''; ?><span class="work-card-client-end"><?php echo esc_html( $card_last ); ?><svg class="work-card-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span></h3>
			<?php if ( $card_description ) : ?><span class="work-card-line"><?php echo esc_html( $card_description ); ?></span><?php endif; ?>
		</span>
	</a>
</article>
