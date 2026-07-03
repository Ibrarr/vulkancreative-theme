<?php
/**
 * The shared dark page-hero band: breadcrumbs eyebrow, display <h1> with the
 * red span highlight and a muted sub-line over the forge glow. Used by the
 * Contact and About templates so every page heading renders one component.
 * Styles live in components/common/_page-hero.scss, keyed off the section
 * class (the content-404 sharing pattern).
 *
 * $args:
 *  - 'heading'    Pre-highlighted heading HTML (see vc_heading_parts()).
 *  - 'subheading' Plain-text sub-line. Optional.
 *  - 'class'      Extra section class (e.g. 'contact-hero') kept as an alias
 *                 for page-specific pre-hide and reveal selectors.
 *  - 'id'         Section id. Defaults to 'top'.
 *  - 'cta_label'  Optional forge button under the sub-line (service pages).
 *  - 'cta_url'    The button's target. Both must be set to render.
 */

$hero_heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$hero_subheading = isset( $args['subheading'] ) ? $args['subheading'] : '';
$hero_class      = trim( 'page-hero ' . ( isset( $args['class'] ) ? $args['class'] : '' ) );
$hero_id         = isset( $args['id'] ) ? $args['id'] : 'top';
$hero_cta_label  = isset( $args['cta_label'] ) ? $args['cta_label'] : '';
$hero_cta_url    = isset( $args['cta_url'] ) ? $args['cta_url'] : '';
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
				<?php if ( $hero_cta_label && $hero_cta_url ) : ?>
					<div class="hero-actions">
						<a class="button" href="<?php echo esc_url( $hero_cta_url ); ?>"><?php echo esc_html( $hero_cta_label ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
