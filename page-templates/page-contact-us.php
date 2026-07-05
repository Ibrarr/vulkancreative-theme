<?php
/*
Template Name: Contact us
*/
get_header();

// Headings are composed from three editable parts (start, red, end) by
// vc_heading_parts() in inc/template-functions.php; the red part renders as
// the standard <span> highlight. The fallbacks only apply when all three
// parts are blank.

// Hero
$hero_heading    = vc_heading_parts( 'ct_hero_heading', false, "Let's build something that <span>performs</span>." );
$hero_subheading = get_field('ct_hero_subheading') ?: 'Tell us where you want to be. We reply within one working day with a clear next step, no pitch decks and no hard sell.';

// Details — company contact info lives in Global Settings (options), so the
// Contact page and the footer share one source of truth.
$email           = get_field('company_email', 'options') ?: 'info@vulkancreative.com';
$phone           = get_field('company_phone', 'options') ?: '020 3576 7525';
$location        = get_field('company_location', 'options') ?: 'Dawson House, 5 Jewry Street, London, EC3N 2EX';
$map_url         = get_field('company_map_url', 'options') ?: 'https://maps.app.goo.gl/gSBBfZt45iUbm2UH7';
// Strip everything but digits and a leading + for the tel: href.
$phone_href      = $phone ? preg_replace( '/[^0-9+]/', '', $phone ) : '';

$next_heading = get_field('ct_next_heading') ?: 'What happens next';
$next_steps_default = [
	[ 'title' => 'We reply within one working day', 'description' => 'A real person reads your message and comes back with first thoughts, not an auto-responder.' ],
	[ 'title' => 'A short discovery call',          'description' => 'We learn how your business runs and what good looks like for you. No charge, no obligation.' ],
	[ 'title' => 'A clear plan and price',          'description' => 'You get a straightforward proposal with scope, timings and cost. You decide if it is a fit.' ],
];
$next_steps = [];
if ( have_rows('ct_next_steps') ) {
	while ( have_rows('ct_next_steps') ) {
		the_row();
		$next_steps[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}
$next_steps = $next_steps ?: $next_steps_default;

// Form
$form_heading = vc_heading_parts( 'ct_form_heading', false, 'Tell us about <span>your project</span>' );
$form_note    = get_field( 'ct_form_note' ) ?: 'It takes about a minute.';

// Inline line icons for the contact channels (consistent stroke, SVG only).
$ct_icons = [
	'email'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
	'phone'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
	'location' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
];
?>

<?php
// The shared page-hero band; 'contact-hero' stays on the section as the alias
// the pre-hide rules in components/_main.scss and contact/reveal.js target.
get_template_part( 'template-parts/page', 'hero', [
	'heading'    => $hero_heading,
	'subheading' => $hero_subheading,
	'class'      => 'contact-hero',
] );
?>

<section class="contact-main" id="contact">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-6 offset-lg-1 contact-form-col form order-1 order-lg-2">
				<h2><?php echo wp_kses_post( $form_heading ); ?></h2>
				<?php if ( $form_note ) : ?>
					<p class="form-note"><?php echo esc_html( $form_note ); ?></p>
				<?php endif; ?>
				<div class="form-container">
					<?php // Progress hairline sits outside .gform_wrapper so GF ajax re-renders never remove it. ?>
					<span class="form-progress" aria-hidden="true"><span class="form-progress-fill"></span></span>
					<?php vc_render_form( 5 ); ?>
				</div>
			</div>

			<div class="col-lg-5 contact-details order-2 order-lg-1">
				<ul class="contact-channels">
					<?php if ( $email ) : ?>
						<li>
							<a class="channel" href="mailto:<?php echo esc_attr( $email ); ?>">
								<span class="channel-icon" aria-hidden="true"><?php echo $ct_icons['email']; ?></span>
								<span class="channel-text">
									<span class="channel-label">Email</span>
									<span class="channel-value"><?php echo esc_html( $email ); ?></span>
								</span>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li>
							<a class="channel" href="tel:<?php echo esc_attr( $phone_href ); ?>">
								<span class="channel-icon" aria-hidden="true"><?php echo $ct_icons['phone']; ?></span>
								<span class="channel-text">
									<span class="channel-label">Phone</span>
									<span class="channel-value"><?php echo esc_html( $phone ); ?></span>
								</span>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<li>
							<?php if ( $map_url ) : ?>
								<a class="channel" href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener">
							<?php else : ?>
								<div class="channel channel--static">
							<?php endif; ?>
								<span class="channel-icon" aria-hidden="true"><?php echo $ct_icons['location']; ?></span>
								<span class="channel-text">
									<span class="channel-label">Where we are</span>
									<span class="channel-value"><?php echo esc_html( $location ); ?></span>
								</span>
							<?php echo $map_url ? '</a>' : '</div>'; ?>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( $next_steps ) : ?>
					<div class="contact-next">
						<h3><?php echo esc_html( $next_heading ); ?></h3>
						<?php // The wrapper keeps the ember rail's coordinate space aligned with the list. ?>
						<div class="next-rail-wrap">
							<span class="next-progress" aria-hidden="true"></span>
							<ol class="contact-next-steps">
								<?php foreach ( $next_steps as $i => $step ) : ?>
									<li class="next-step">
										<span class="step-index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
										<span class="step-body">
											<span class="step-title"><?php echo esc_html( $step['title'] ); ?></span>
											<span class="step-desc"><?php echo esc_html( $step['description'] ); ?></span>
										</span>
									</li>
								<?php endforeach; ?>
							</ol>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
