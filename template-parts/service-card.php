<?php
/**
 * Shared service card: one service taxonomy term as a linked card. Used by
 * the services hub grid (variant 'grid') and the service pages' related
 * strip (variant 'related'). Styles live in common/_service-card.scss.
 *
 * $args:
 *  - 'term'       WP_Term from the service taxonomy. Required.
 *  - 'index'      1-based position, rendered as the outlined numeral. Required.
 *  - 'variant'    'grid' (default) or 'related'.
 *  - 'show_index' Render the numeral. Defaults true; the related strip passes
 *                 false so its cards match the hub grid minus the numbering.
 */

$card_term = isset( $args['term'] ) ? $args['term'] : null;
if ( ! $card_term instanceof WP_Term ) {
	return;
}

$card_link = get_term_link( $card_term );
if ( is_wp_error( $card_link ) ) {
	return;
}

$card_index      = isset( $args['index'] ) ? (int) $args['index'] : 1;
$card_variant    = ( isset( $args['variant'] ) && 'related' === $args['variant'] ) ? 'related' : 'grid';
$card_show_index = ! isset( $args['show_index'] ) || false !== $args['show_index'];

$card_desc = wp_strip_all_tags( term_description( $card_term->term_id ) );

$card_icon     = get_field( 'icon', 'service_' . $card_term->term_id );
$card_icon_url = $card_icon ? VC_TEMPLATE_URI . '/assets/images/icons/services/' . ltrim( $card_icon, '/' ) : '';
?>

<a class="service-card service-card--<?php echo esc_attr( $card_variant ); ?>" href="<?php echo esc_url( $card_link ); ?>">
	<?php if ( $card_icon_url ) : ?>
		<img class="service-card-icon" loading="lazy" src="<?php echo esc_url( $card_icon_url ); ?>" alt="" aria-hidden="true">
	<?php endif; ?>
	<?php if ( $card_show_index ) : ?>
		<span class="service-card-index" aria-hidden="true"><?php echo esc_html( str_pad( $card_index, 2, '0', STR_PAD_LEFT ) ); ?></span>
	<?php endif; ?>
	<span class="service-card-body">
		<h3 class="service-card-title"><?php echo esc_html( $card_term->name ); ?></h3>
		<?php if ( $card_desc ) : ?>
			<p class="service-card-desc"><?php echo esc_html( $card_desc ); ?></p>
		<?php endif; ?>
	</span>
	<span class="service-card-arrow" aria-hidden="true">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
	</span>
</a>
