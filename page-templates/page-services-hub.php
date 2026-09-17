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
$hero_heading    = vc_heading_parts( 'sh_hero_heading', false, 'Digital marketing services from <span>one London team</span>' );
$hero_subheading = get_field('sh_hero_subheading') ?: 'Six services from one London team: web design and development, SEO and AI search, paid media, content and social, branding, and strategy and analytics. Take one or combine them.';

// Services grid
$grid_heading   = vc_heading_parts( 'sh_grid_heading', false, 'What each <span>service</span> covers' );
$grid_statement = get_field('sh_grid_statement') ?: 'Pick one service or combine several. The same two founders lead all of it.';
$grid_support   = get_field('sh_grid_support') ?: 'Every discipline here is handled by the same team, so nothing gets lost between agencies and you always know who to call.';

// The directory lists the parent services in the order set in Global Settings >
// Service List (parents ticked to show on the hub), each with its child
// services in their editable order (vc_service_children()).
$services = vc_ordered_services( 'hub' );

// Process (steps cross-read from the homepage so the site keeps one process)
$process_heading    = vc_heading_parts( 'sh_process_heading', false, 'How every <span>project</span> runs' );
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
		[ 'title' => 'Strategy',  'description' => 'We set the plan: positioning, priorities and the channels most likely to bring enquiries.' ],
		[ 'title' => 'Build',     'description' => 'We design and develop the brand, website and campaigns, built bespoke around your audience.' ],
		[ 'title' => 'Optimise',  'description' => 'We measure enquiries, rankings and revenue each month and adjust the work based on what the numbers show.' ],
	];
}

// Proof (the rating chip reads Global Settings via vc_google_reviews())
$proof_heading = vc_heading_parts( 'sh_proof_heading', false, 'What <span>clients</span> say' );

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
		<?php // A directory, not a card grid: one full-width row per pillar with its
		// child services as links. The pillar link stretches over the row, the
		// child links sit above it, and the arrow is decoration. ?>
		<ul class="services-directory">
			<?php foreach ( $services as $service ) :
				$directory_desc     = wp_strip_all_tags( term_description( $service->term_id, 'service' ) );
				$directory_children = vc_service_children( $service->term_id );
				?>
				<li class="directory-row">
					<h3 class="directory-name"><a class="directory-link" href="<?php echo esc_url( get_term_link( $service ) ); ?>"><?php echo esc_html( $service->name ); ?></a></h3>
					<div class="directory-body">
						<?php if ( $directory_desc ) : ?>
							<p class="directory-desc"><?php echo esc_html( $directory_desc ); ?></p>
						<?php endif; ?>
						<?php if ( $directory_children ) : ?>
							<ul class="directory-children" aria-label="<?php echo esc_attr( $service->name ); ?> services">
								<?php foreach ( $directory_children as $directory_child ) : ?>
									<li><a href="<?php echo esc_url( get_term_link( $directory_child ) ); ?>"><?php echo esc_html( $directory_child->name ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
					<span class="directory-arrow" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php get_template_part( 'template-parts/offer-links' ); ?>
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
							<?php // Below the fold here, so every slide stays lazy (index offset past the eager six).
							$logo_i = 6;
							while ( have_rows( 'worked_with_logos', 'options' ) ) : the_row();
								echo vc_logo_slide( get_sub_field( 'logo' ), $logo_i++ );
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
