<?php
/**
 * Case study card: one metric-forward tile linking to the case study's own
 * page, used by the /case-studies/ archive grid and the service term pages'
 * case-studies strip. Deliberately a different anatomy from the work card:
 * the image panel sits above a bordered body that leads with the headline
 * metric, because on this card the number is the story. Hover is an inner
 * image zoom and colour shifts only (the work tiles' saturation hover is
 * grandfathered and does not spread here; tint never changes). Styles in
 * components/common/_case-study-card.scss; assets/js/case-study/filter.js
 * reads data-services when filtering the archive grid.
 *
 * $args:
 *  - 'case'    Post ID. Required.
 *  - 'class'   Column classes for the article. Defaults to the archive's
 *              two-up ('col-12 col-md-6'); the service strip passes three-up.
 *  - 'hidden'  Server-rendered filter state (true = filtered out).
 */

$cs_card_id = (int) ( $args['case'] ?? 0 );
if ( ! $cs_card_id ) {
	return;
}

$cs_card_image = get_field( 'cs_image', $cs_card_id );
if ( empty( $cs_card_image ) ) {
	return;
}

$cs_card_client  = get_field( 'cs_client_name', $cs_card_id ) ?: get_the_title( $cs_card_id );
$cs_card_sector  = get_field( 'cs_sector', $cs_card_id );
$cs_card_summary = get_field( 'cs_summary', $cs_card_id );
$cs_card_value   = get_field( 'cs_metric_value', $cs_card_id );
$cs_card_label   = get_field( 'cs_metric_label', $cs_card_id );
$cs_card_class   = $args['class'] ?? 'col-12 col-md-6';
$cs_card_hidden  = ! empty( $args['hidden'] );

$cs_card_terms = get_the_terms( $cs_card_id, 'service' );
$cs_card_slugs = ( $cs_card_terms && ! is_wp_error( $cs_card_terms ) ) ? wp_list_pluck( $cs_card_terms, 'slug' ) : [];

// Keep the arrow glued to the last word of the name.
$cs_card_words = explode( ' ', $cs_card_client );
$cs_card_last  = array_pop( $cs_card_words );
$cs_card_head  = implode( ' ', $cs_card_words );
?>
<article class="cs-card-item <?php echo esc_attr( $cs_card_class ); ?>" data-services="<?php echo esc_attr( implode( ' ', $cs_card_slugs ) ); ?>"<?php echo $cs_card_hidden ? ' hidden' : ''; ?>>
	<a class="cs-card" href="<?php echo esc_url( get_permalink( $cs_card_id ) ); ?>">
		<span class="cs-card-media">
			<?php echo wp_get_attachment_image( $cs_card_image['ID'], 'large', false, [
				'class'   => 'cs-card-img',
				'loading' => 'lazy',
				'alt'     => '',
			] ); ?>
			<span class="cs-card-scrim" aria-hidden="true"></span>
			<?php if ( $cs_card_sector ) : ?><span class="cs-card-plate"><?php echo esc_html( $cs_card_sector ); ?></span><?php endif; ?>
		</span>
		<span class="cs-card-body">
			<?php if ( $cs_card_value ) : ?>
				<span class="cs-card-metric">
					<span class="cs-card-metric-value"><?php echo esc_html( $cs_card_value ); ?></span>
					<?php if ( $cs_card_label ) : ?><span class="cs-card-metric-label"><?php echo esc_html( $cs_card_label ); ?></span><?php endif; ?>
				</span>
			<?php endif; ?>
			<h3 class="cs-card-client"><?php echo $cs_card_head ? esc_html( $cs_card_head ) . ' ' : ''; ?><span class="cs-card-client-end"><?php echo esc_html( $cs_card_last ); ?><svg class="cs-card-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span></h3>
			<?php if ( $cs_card_summary ) : ?><span class="cs-card-line"><?php echo esc_html( $cs_card_summary ); ?></span><?php endif; ?>
		</span>
	</a>
</article>
