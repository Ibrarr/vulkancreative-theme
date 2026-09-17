<?php
/*
Template Name: Services Hub
*/
get_header();

// Section headings are composed from three editable parts (start, red, end)
// by vc_heading_parts() in inc/template-functions.php; the red part renders
// as the standard <span> highlight. The fallbacks only apply when all three
// parts are blank. The grid statement stays a single field: if it is empty
// or still the plain default, the span-highlighted version is used.

// The homepage is the single source for the sitewide process steps; the
// rating chip reads the Global Settings Reviews fields via vc_google_reviews().
$front_page_id = (int) get_option( 'page_on_front' );

// Hero
$hero_heading    = vc_heading_parts( 'sh_hero_heading', false, 'What we <span>do</span>.' );
$hero_subheading = get_field('sh_hero_subheading') ?: 'Strategy, design and marketing that work together. We build systems that turn attention into action and visitors into customers.';

// Services grid
$grid_heading   = vc_heading_parts( 'sh_grid_heading', false, 'Six services. <span>One team</span>.' );
$grid_statement = get_field('sh_grid_statement');
if ( ! $grid_statement || 'Everything you need to grow, under one roof.' === $grid_statement ) {
	$grid_statement = 'Everything you need to grow, <span>under one roof</span>.';
}
$grid_support   = get_field('sh_grid_support') ?: 'Pick the service you need now or combine them. Every discipline here is delivered in-house by the same team, so nothing gets lost between agencies.';

// The grid lists the parent services in the order set in Global Settings >
// Service List (parents ticked to show on the hub). Each parent's children
// live on its own page.
$services = vc_ordered_services( 'hub' );

// Process (steps cross-read from the homepage so the site keeps one process)
$process_heading    = vc_heading_parts( 'sh_process_heading', false, 'From brief to <span>results</span>.' );
$process_subheading = get_field('sh_process_subheading') ?: 'The same clear process behind every service, with one partner accountable the whole way.';

$process_steps = [];
if ( have_rows( 'hp_process_steps', $front_page_id ) ) {
	while ( have_rows( 'hp_process_steps', $front_page_id ) ) {
		the_row();
		$process_steps[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}
if ( ! $process_steps ) {
	$process_steps = [
		[ 'title' => 'Discover',  'description' => 'We get to know your business, your customers and your goals, and audit where you are now.' ],
		[ 'title' => 'Strategy',  'description' => 'We set the plan: positioning, priorities and the channels that will actually move the needle.' ],
		[ 'title' => 'Build',     'description' => 'We design and develop the brand, website and campaigns, built bespoke around your audience.' ],
		[ 'title' => 'Optimise',  'description' => 'We measure what matters and refine continuously, so results compound over time.' ],
	];
}

// Proof (the rating chip reads Global Settings via vc_google_reviews())
$proof_heading = vc_heading_parts( 'sh_proof_heading', false, '<span>Proof</span>, not promises.' );

$testimonial_items = vc_testimonial_items( 6 );

// CTA
$cta_heading    = vc_heading_parts( 'sh_cta_heading', false, 'Start a <span>project</span>.' );
$cta_subheading = get_field('sh_cta_subheading') ?: "Tell us what you're trying to achieve and we'll come back within one working day with an honest view on how we'd approach it.";
?>

<?php
// The shared page-hero band; 'hub-hero' stays on the section as the alias the
// pre-hide rules in services-hub/_services-hub.scss and the hub reveal.js target.
get_template_part( 'template-parts/page', 'hero', [
	'heading'    => $hero_heading,
	'subheading' => $hero_subheading,
	'class'      => 'hub-hero',
] );
?>

<section class="hub-services surface-white" id="services">
	<div class="container px-4">
		<div class="row hub-services-head">
			<div class="col-lg-6">
				<div class="content">
					<h2><?php echo wp_kses_post( $grid_heading ); ?></h2>
				</div>
			</div>
			<div class="col-lg-5 offset-lg-1">
				<div class="intro-lead">
					<p class="intro-statement"><?php echo wp_kses_post( $grid_statement ); ?></p>
					<p class="intro-support"><?php echo esc_html( $grid_support ); ?></p>
				</div>
			</div>
		</div>
		<div class="row g-4 services-grid">
			<?php
			$card_i = 1;
			foreach ( $services as $service ) {
				echo '<div class="col-lg-4 col-md-6 col-12 service-card-col">';
				get_template_part( 'template-parts/service', 'card', [
					'term'       => $service,
					'index'      => $card_i,
					'variant'    => 'grid',
					// Services are an unordered set, so the outlined 01-06 numeral
					// reads as decorative scaffolding; the varying watermark icon
					// and title carry the card instead.
					'show_index' => false,
				] );
				echo '</div>';
				$card_i++;
			}
			?>
		</div>
	</div>
</section>

<section class="process hub-process surface-grey" id="process">
	<div class="container px-4">
		<div class="content">
			<h2><?php echo wp_kses_post( $process_heading ); ?></h2>
			<p class="sub-heading"><?php echo esc_html( $process_subheading ); ?></p>
		</div>
		<div class="process-steps">
			<div class="process-progress" aria-hidden="true"></div>
			<?php $step_i = 1; foreach ( $process_steps as $step ) : ?>
				<div class="process-step">
					<span class="step-number"><?php echo str_pad( $step_i, 2, '0', STR_PAD_LEFT ); ?></span>
					<div class="step-content">
						<h3><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['description'] ); ?></p>
					</div>
				</div>
			<?php $step_i++; endforeach; ?>
		</div>
	</div>
</section>

<section class="hub-proof surface-white" id="proof">
	<div class="container px-4">
		<div class="proof-head">
			<div class="content">
				<h2><?php echo wp_kses_post( $proof_heading ); ?></h2>
			</div>
			<?php get_template_part( 'template-parts/rating-chip' ); ?>
		</div>
		<?php if ( $testimonial_items ) : ?>
			<?php get_template_part( 'template-parts/testimonial-spotlight', null, [ 'items' => $testimonial_items ] ); ?>
		<?php endif; ?>
		<?php if ( have_rows( 'worked_with_logos', 'options' ) ) : ?>
			<div class="hub-logos">
				<div class="splide" id="logo-splide" aria-label="Companies we've worked with">
					<div class="splide__track">
						<ul class="splide__list">
							<?php while ( have_rows( 'worked_with_logos', 'options' ) ) : the_row();
								$logo = get_sub_field( 'logo' );
								if ( $logo ) : ?>
									<li class="splide__slide">
										<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ?: $logo['title'] ); ?>" loading="lazy">
									</li>
								<?php endif;
							endwhile; ?>
						</ul>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="hub-cta surface-grey" id="sh-contact">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-6">
				<div class="content">
					<h2><?php echo wp_kses_post( $cta_heading ); ?></h2>
					<p class="sub-heading"><?php echo esc_html( $cta_subheading ); ?></p>
				</div>
			</div>
			<div class="col-lg-6 form">
				<div class="form-container">
					<?php vc_render_form( 2 ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
// ItemList of the six services, so the hub reads as a collection of the
// Service nodes each term page emits (the About Person-nodes pattern).
$service_list_items = [];
$service_pos        = 1;
foreach ( $services as $service ) {
	$service_link = get_term_link( $service );
	if ( is_wp_error( $service_link ) ) {
		continue;
	}
	$service_list_items[] = [
		'@type'    => 'ListItem',
		'position' => $service_pos,
		'name'     => $service->name,
		'url'      => $service_link,
	];
	$service_pos++;
}
if ( $service_list_items ) {
	echo '<script type="application/ld+json">'
		. wp_json_encode( [
			'@context'        => 'https://schema.org',
			'@type'           => 'ItemList',
			'@id'             => get_permalink() . '#services-list',
			'name'            => 'Vulkan Creative services',
			'itemListElement' => $service_list_items,
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>';
}

get_footer();
