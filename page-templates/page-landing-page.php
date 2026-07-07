<?php
/*
Template Name: Landing Page
*/
get_header();

// Reusable landing page: a stack of forge-styled blocks composed in the ACF
// Flexible Content field lp_sections (registered in inc/landing-page-fields.php).
// Each row is resolved into a $block array here (inside the_row(), so sub-fields
// read correctly) and handed to a dumb partial in template-parts/blocks/. Hero
// and CTA are dark anchor bands in both modes and do not advance the light/dark
// surface rhythm; every other block alternates the surface pair.

$lp_surface_i = 0;            // alternation counter for content blocks
$lp_faqs      = [];           // aggregated Q&A for one FAQPage node
$lp_testimonial_shown = false; // the shared spotlight binds a single id

// Pass 1: collect the layout sequence so the LAST cta can carry the sticky-bar
// anchor (#lp-cta) and the hero/header CTAs can point at it.
$lp_seq = [];
if ( have_rows( 'lp_sections' ) ) {
	while ( have_rows( 'lp_sections' ) ) { the_row(); $lp_seq[] = get_row_layout(); }
}
$lp_last_cta = -1;
foreach ( $lp_seq as $k => $l ) { if ( 'cta' === $l ) { $lp_last_cta = $k; } }

// Pass 2: render.
if ( have_rows( 'lp_sections' ) ) :
	$row_i = -1;
	while ( have_rows( 'lp_sections' ) ) : the_row();
		$row_i++;
		$layout = get_row_layout();

		if ( 'hero' === $layout || 'cta' === $layout ) {
			$surface = 'dark';
		} else {
			$surface = ( 0 === $lp_surface_i % 2 ) ? 'a' : 'b';
			$lp_surface_i++;
		}

		$block = [ 'layout' => $layout, 'surface' => $surface, 'index' => $row_i ];

		switch ( $layout ) {
			case 'hero':
				$block['heading']          = vc_heading_parts_sub( 'heading', 'Build <span>something</span>.' );
				$block['subheading']       = get_sub_field( 'subheading' );
				$block['primary_label']    = get_sub_field( 'primary_label' );
				$block['primary_target']   = get_sub_field( 'primary_target' ) ?: '#lp-cta';
				$block['secondary_label']  = get_sub_field( 'secondary_label' );
				$block['secondary_target'] = get_sub_field( 'secondary_target' );
				$block['note']             = get_sub_field( 'note' );
				$block['mark_text']        = get_sub_field( 'mark_text' );
				$block['show_logos']       = get_sub_field( 'show_logos' );
				break;

			case 'intro':
				$block['heading'] = vc_heading_parts_sub( 'heading', '' );
				$block['body']    = get_sub_field( 'body' );
				break;

			case 'steps':
				$block['heading']    = vc_heading_parts_sub( 'heading', 'How it <span>works</span>.' );
				$block['subheading'] = get_sub_field( 'subheading' );
				$block['steps']      = [];
				if ( have_rows( 'steps' ) ) {
					while ( have_rows( 'steps' ) ) { the_row();
						$block['steps'][] = [ 'title' => get_sub_field( 'title' ), 'description' => get_sub_field( 'description' ) ];
					}
				}
				break;

			case 'checklist':
				$block['heading']     = vc_heading_parts_sub( 'heading', 'What you <span>get</span>.' );
				$block['subheading']  = get_sub_field( 'subheading' );
				$block['stamp']       = get_sub_field( 'included_label' );
				$block['items']       = [];
				if ( have_rows( 'items' ) ) {
					while ( have_rows( 'items' ) ) { the_row();
						$block['items'][] = [ 'item' => get_sub_field( 'item' ), 'detail' => get_sub_field( 'detail' ) ];
					}
				}
				$block['show_total']  = get_sub_field( 'show_total' );
				$block['total_label'] = get_sub_field( 'total_label' );
				$block['total_value'] = get_sub_field( 'total_value' );
				$block['total_note']  = get_sub_field( 'total_note' );
				break;

			case 'comparison':
				$block['heading']      = vc_heading_parts_sub( 'heading', 'The <span>difference</span>.' );
				$block['note']         = get_sub_field( 'note' );
				$block['usual_title']  = get_sub_field( 'usual_title' );
				$block['vulkan_title'] = get_sub_field( 'vulkan_title' );
				$block['rows']         = [];
				if ( have_rows( 'rows' ) ) {
					while ( have_rows( 'rows' ) ) { the_row();
						$block['rows'][] = [ 'usual' => get_sub_field( 'usual' ), 'vulkan' => get_sub_field( 'vulkan' ) ];
					}
				}
				break;

			case 'stats':
				$block['heading']    = vc_heading_parts_sub( 'heading', 'The <span>results</span>.' );
				$block['subheading'] = get_sub_field( 'subheading' );
				$block['stats']      = [];
				if ( have_rows( 'stats' ) ) {
					while ( have_rows( 'stats' ) ) { the_row();
						$block['stats'][] = [
							'number' => get_sub_field( 'number' ),
							'prefix' => get_sub_field( 'prefix' ),
							'suffix' => get_sub_field( 'suffix' ),
							'label'  => get_sub_field( 'label' ),
						];
					}
				}
				break;

			case 'testimonials':
				$block['heading']        = vc_heading_parts_sub( 'heading', 'What our <span>clients say</span>.' );
				$block['allow_carousel'] = ! $lp_testimonial_shown;
				$lp_testimonial_shown    = true;
				$source = get_sub_field( 'source' ) ?: 'cpt';
				$count  = (int) ( get_sub_field( 'count' ) ?: 6 );
				$items  = [];
				if ( 'manual' === $source && have_rows( 'items' ) ) {
					while ( have_rows( 'items' ) ) { the_row();
						$photo = get_sub_field( 'photo' );
						$items[] = [
							'quote'   => get_sub_field( 'quote' ),
							'name'    => get_sub_field( 'name' ),
							'company' => trim( get_sub_field( 'role' ) . ', ' . get_sub_field( 'company' ), ', ' ),
							'photo'   => $photo['sizes']['medium'] ?? $photo['url'] ?? VC_TEMPLATE_URI . '/assets/images/testimonials/avatar-placeholder.png',
						];
					}
				} else {
					$tq = new WP_Query( [ 'post_type' => 'testimonial', 'posts_per_page' => $count, 'no_found_rows' => true ] );
					while ( $tq->have_posts() ) { $tq->the_post();
						$photo = get_field( 'tm_photo' );
						$items[] = [
							'quote'   => get_field( 'tm_quote' ),
							'name'    => get_field( 'tm_name' ),
							'company' => trim( get_field( 'tm_role' ) . ', ' . get_field( 'tm_company' ), ', ' ),
							'photo'   => $photo['sizes']['medium'] ?? $photo['url'] ?? VC_TEMPLATE_URI . '/assets/images/testimonials/avatar-placeholder.png',
						];
					}
					wp_reset_postdata();
				}
				$block['items'] = $items;
				break;

			case 'faq':
				$block['heading']    = vc_heading_parts_sub( 'heading', '<span>FAQs</span>.' );
				$block['cta_label']  = get_sub_field( 'cta_label' );
				$block['cta_target'] = get_sub_field( 'cta_target' ) ?: '#lp-cta';
				$block['faqs']       = [];
				if ( have_rows( 'faqs' ) ) {
					while ( have_rows( 'faqs' ) ) { the_row();
						$qa = [ 'question' => get_sub_field( 'question' ), 'answer' => get_sub_field( 'answer' ) ];
						$block['faqs'][] = $qa;
						$lp_faqs[]       = $qa;
					}
				}
				break;

			case 'cta':
				$block['heading']        = vc_heading_parts_sub( 'heading', 'Ready to <span>get started</span>?' );
				$block['subheading']     = get_sub_field( 'subheading' );
				$block['mode']           = get_sub_field( 'mode' ) ?: 'form';
				$block['note']           = get_sub_field( 'note' );
				$block['primary_label']  = get_sub_field( 'primary_label' );
				$block['primary_target'] = get_sub_field( 'primary_target' );
				$block['is_anchor']      = ( $row_i === $lp_last_cta );
				break;
		}

		get_template_part( 'template-parts/blocks/' . $layout, null, $block );

	endwhile;
endif;

// Mobile sticky CTA — only when a CTA anchor exists to scroll to.
if ( $lp_last_cta >= 0 ) :
	$sticky_label = get_field( 'lp_header_cta_label' ) ?: 'Get in Touch';
	?>
	<div class="lp-sticky-cta">
		<a class="button" href="#lp-cta"><?php echo esc_html( $sticky_label ); ?></a>
	</div>
	<?php
endif;

// Aggregated FAQPage JSON-LD from every FAQ block on the page.
$faq_entities = [];
foreach ( $lp_faqs as $qa ) {
	$q = trim( wp_strip_all_tags( (string) $qa['question'] ) );
	$a = trim( wp_strip_all_tags( (string) $qa['answer'] ) );
	if ( '' === $q || '' === $a ) { continue; }
	$faq_entities[] = [ '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $a ] ];
}
if ( $faq_entities ) {
	echo '<script type="application/ld+json">'
		. wp_json_encode( [ '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq_entities ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>' . "\n";
}

get_footer();
