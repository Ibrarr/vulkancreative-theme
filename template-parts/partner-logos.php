<?php
// Reusable platform-partner badge strip, fed by Global Settings > Partner
// Logos. Official colour badges as issued: never filtered or recoloured; the
// quietness comes from the hairline seam, the muted label and spacing.
// Args: 'surface' => 'dark' for always-dark hosts (the homepage why band) or
// 'auto' (default) for light sections with the standard dark-mode pairing.
// 'label' overrides the Global Settings strip label for one placement.
// On an auto surface a badge shows in both modes by default; a row flagged
// "Dark surfaces only" (white artwork) hides in light mode unless it carries
// a light-surface file, which then renders there instead. Dark hosts always
// show every row's main badge. Sizing is area-based: wide lockups (ratio >=
// 1.6) take the short height class so a wordmark and a round badge carry the
// same optical weight.
if ( ! function_exists( 'have_rows' ) || ! have_rows( 'partner_logos', 'options' ) ) {
	return;
}
$surface = $args['surface'] ?? 'auto';
$on_dark = 'dark' === $surface;
$label   = $args['label'] ?? ( get_field( 'partner_logos_heading', 'options' ) ?: 'Official partners of the platforms we build on' );

$partner_img = function ( $img, $name, $extra_class = '' ) {
	$ratio = ( ! empty( $img['height'] ) && $img['height'] > 0 ) ? $img['width'] / $img['height'] : 1;
	$class = trim( ( $ratio >= 1.6 ? 'is-wide' : '' ) . ' ' . $extra_class );
	echo '<img loading="lazy"' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' )
		. ' src="' . esc_url( $img['url'] ) . '"'
		. ' alt="' . esc_attr( $name ?: ( $img['alt'] ?: $img['title'] ) ) . '"'
		. ' width="' . (int) $img['width'] . '" height="' . (int) $img['height'] . '">';
};
?>
<div class="partner-logos<?php echo $on_dark ? ' is-on-dark' : ''; ?>">
	<?php if ( $label ) : ?>
		<p class="partner-logos-label"><?php echo esc_html( $label ); ?></p>
	<?php endif; ?>
	<ul class="partner-logos-row">
		<?php while ( have_rows( 'partner_logos', 'options' ) ) : the_row();
			$logo       = get_sub_field( 'logo' );
			$logo_light = get_sub_field( 'logo_light' );
			$name       = get_sub_field( 'name' );
			$url        = get_sub_field( 'url' );
			if ( ! $logo ) {
				continue;
			}
			$dark_only = ! $on_dark && ! $logo_light && get_sub_field( 'dark_only' );
			$two_files = ! $on_dark && $logo_light;
			?>
			<li class="partner-logos-item<?php echo $dark_only ? ' is-dark-only' : ''; ?>">
				<?php if ( $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ( $name ?: 'Partner' ) . ' (opens in a new tab)' ); ?>">
				<?php endif; ?>
				<?php if ( $two_files ) {
					$partner_img( $logo_light, $name, 'is-on-light-file' );
					$partner_img( $logo, $name, 'is-on-dark-file' );
				} else {
					$partner_img( $logo, $name );
				} ?>
				<?php if ( $url ) : ?>
					</a>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
