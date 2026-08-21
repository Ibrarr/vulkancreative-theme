<?php
// Reusable platform-partner badge strip, fed by Global Settings > Partner
// Logos. Official colour badges as issued: never filtered or recoloured; the
// quietness comes from the hairline seam, the muted label and spacing.
// Args: 'surface' => 'dark' for always-dark hosts (the homepage why band) or
// 'auto' (default) for light sections with the standard dark-mode pairing;
// 'variant' => 'footer' for the compact footer group (its own label field in
// the footer-heading style, smaller badges, no seam of its own); 'label'
// overrides the label for one placement. Sizing is area-based: wide lockups
// (ratio >= 1.6) take the short height class so a wordmark and a round badge
// carry the same optical weight.
if ( ! function_exists( 'have_rows' ) || ! have_rows( 'partner_logos', 'options' ) ) {
	return;
}
$surface   = $args['surface'] ?? 'auto';
$variant   = $args['variant'] ?? '';
$is_footer = 'footer' === $variant;
$on_dark   = 'dark' === $surface || $is_footer;
if ( isset( $args['label'] ) ) {
	$label = $args['label'];
} elseif ( $is_footer ) {
	$label = get_field( 'partner_logos_footer_label', 'options' ) ?: 'Official partners';
} else {
	$label = get_field( 'partner_logos_heading', 'options' ) ?: 'Official partners of the platforms we build on';
}
$classes = 'partner-logos' . ( $on_dark ? ' is-on-dark' : '' ) . ( $is_footer ? ' is-footer' : '' );
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( $label ) : ?>
		<p class="partner-logos-label"><?php echo esc_html( $label ); ?></p>
	<?php endif; ?>
	<ul class="partner-logos-row">
		<?php while ( have_rows( 'partner_logos', 'options' ) ) : the_row();
			$logo = get_sub_field( 'logo' );
			$name = get_sub_field( 'name' );
			$url  = get_sub_field( 'url' );
			if ( ! $logo ) {
				continue;
			}
			$ratio = ( ! empty( $logo['height'] ) && $logo['height'] > 0 ) ? $logo['width'] / $logo['height'] : 1;
			?>
			<li class="partner-logos-item">
				<?php if ( $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ( $name ?: 'Partner' ) . ' (opens in a new tab)' ); ?>">
				<?php endif; ?>
				<img loading="lazy"<?php echo $ratio >= 1.6 ? ' class="is-wide"' : ''; ?> src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $name ?: ( $logo['alt'] ?: $logo['title'] ) ); ?>" width="<?php echo (int) $logo['width']; ?>" height="<?php echo (int) $logo['height']; ?>">
				<?php if ( $url ) : ?>
					</a>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
