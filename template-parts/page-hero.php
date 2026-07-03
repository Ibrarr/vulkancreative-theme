<?php
/**
 * The shared dark page-hero band: breadcrumbs eyebrow, display <h1> with the
 * red span highlight and a muted sub-line over the forge glow. Used by the
 * Contact and About templates so every page heading renders one component.
 * Styles live in components/common/_page-hero.scss, keyed off the section
 * class (the content-404 sharing pattern).
 *
 * $args:
 *  - 'heading'    Pre-highlighted heading HTML (see the ct_heading() pattern).
 *  - 'subheading' Plain-text sub-line. Optional.
 *  - 'class'      Extra section class (e.g. 'contact-hero') kept as an alias
 *                 for page-specific pre-hide and reveal selectors.
 *  - 'id'         Section id. Defaults to 'top'.
 */

$hero_heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$hero_subheading = isset( $args['subheading'] ) ? $args['subheading'] : '';
$hero_class      = trim( 'page-hero ' . ( isset( $args['class'] ) ? $args['class'] : '' ) );
$hero_id         = isset( $args['id'] ) ? $args['id'] : 'top';
?>

<section class="<?php echo esc_attr( $hero_class ); ?>" id="<?php echo esc_attr( $hero_id ); ?>">
	<div class="page-hero-glow" aria-hidden="true"></div>
	<div class="container px-4">
		<div class="row">
			<div class="col-lg-9 content">
				<div class="breadcrumbs"><?php echo do_shortcode( '[wpseo_breadcrumb]' ); ?></div>
				<h1><?php echo wp_kses_post( $hero_heading ); ?></h1>
				<?php if ( $hero_subheading ) : ?>
					<p class="sub-heading"><?php echo esc_html( $hero_subheading ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
