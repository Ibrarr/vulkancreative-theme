<?php
/*
Template Name: About Us
*/
get_header();

// Section headings are composed from three editable parts (start, red, end)
// by vc_heading_parts() in inc/template-functions.php; the red part renders
// as the standard <span> highlight. The fallbacks only apply when all three
// parts are blank. The intro statement stays a single field: if it is empty
// or still the plain default, the span-highlighted version is used.

// Hero
$hero_heading    = vc_heading_parts( 'ab_hero_heading', false, "The people you'll <span>actually</span> work with." );
$hero_subheading = get_field('ab_hero_subheading') ?: 'Vulkan is an in-house agency run by its two founders. You deal with us from the first call to the final build, and nothing gets handed off or watered down.';

// Intro
$intro_statement = get_field('ab_intro_statement');
if ( ! $intro_statement || 'Everything in-house. Everyone accountable.' === $intro_statement ) {
	$intro_statement = 'Everything <span>in-house</span>. Everyone accountable.';
}
$intro_support   = get_field('ab_intro_support') ?: 'Most agencies put layers between you and the people doing the work. We built Vulkan to remove them. Strategy, design, development, content, SEO and paid media all happen in-house, led by the two of us, so decisions move quickly, standards stay ours and nobody can pass the buck.';

// Founders
$founders_heading  = vc_heading_parts( 'ab_founders_heading', false, "Who you'll <span>work with</span>." );
$founders_fallback = [
	[
		'name'      => 'Flynn Forster',
		'role'      => 'Co-Founder · Paid Media & PPC',
		'short_bio' => 'Flynn runs the paid side of Vulkan: paid search, PPC and the campaigns that turn ad budgets into enquiries. More than ten years of managing spend have made him allergic to waste. If a campaign does not return, it does not run.',
		'long_bio'  => '<p>Flynn has spent more than ten years planning and running paid campaigns: Google Ads, paid social and the digital marketing that sits around them. At Vulkan he owns everything paid, from first keyword research to the weekly optimisation calls, so the person who builds your campaign is the same person answering for its results.</p><p>He is direct about budgets. Before anything goes live you will know what we expect it to return, and once it is live you will see plain-English reports that show spend, results and what changes next. No dashboards you need a translator for, and no burying a bad month.</p><p>Flynn is also the one who will sit at your table and ask the awkward commercial questions early, because campaigns built on honest answers perform better.</p>',
		'photo'     => '',
		'linkedin'  => 'https://www.linkedin.com/in/flynn-forster/',
		'email'     => 'flynn@vulkancreative.com',
		'phone'     => '07538 987424',
	],
	[
		'name'      => 'Ibrarr Khan',
		'role'      => 'Co-Founder · Web, SEO & AI',
		'short_bio' => 'Ibrarr leads design, development and search: the websites Vulkan builds and the visibility that makes them earn. He has built sites for more than ten years, and pairs that craft with SEO and AI so every launch keeps earning.',
		'long_bio'  => '<p>Ibrarr has designed and built websites for more than ten years, and at Vulkan he owns that whole side of the studio: design, development, SEO and the practical use of AI. When we build your site, his hands are on it, from the first wireframe to the code that ships.</p><p>His rule is that a site has to earn its keep. Design decisions get made for clarity and conversion, builds are fast and accessible, and search is planned in from the start rather than bolted on. As AI changes how people find and choose companies, he keeps Vulkan\'s clients visible in the answers as well as the rankings.</p><p>Ask him how something works and you will get a straight explanation, not jargon. He builds it, so he can explain it.</p>',
		'photo'     => '',
		'linkedin'  => 'https://www.linkedin.com/in/ibrarr-khan/',
		'email'     => 'ibrarr@vulkancreative.com',
		'phone'     => '07804 676084',
	],
];
$founders = [];
if ( have_rows('ab_founders') ) {
	while ( have_rows('ab_founders') ) {
		the_row();
		$photo      = get_sub_field('photo');
		$founders[] = [
			'name'      => get_sub_field('name'),
			'role'      => get_sub_field('role'),
			'short_bio' => get_sub_field('short_bio'),
			'long_bio'  => get_sub_field('long_bio'),
			'photo'     => $photo ? $photo['url'] : '',
			'linkedin'  => get_sub_field('linkedin_url'),
			'email'     => get_sub_field('email'),
			'phone'     => get_sub_field('phone'),
		];
	}
}
$founders = $founders ?: $founders_fallback;
// The testimonial placeholder headshot reads as intentional under the duotone
// treatment; real photos replace it via the Media Library.
$founder_photo_fallback = VC_TEMPLATE_URI . '/assets/images/testimonials/avatar-placeholder.webp';

// Solid icons supplied by Ibrar (Font Awesome Free 7.3.0) for the founder
// contact rows; fill inherits currentColor so the plate carries the red.
$ab_icons = [
	'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor" aria-hidden="true" focusable="false"><path d="M196.3 512L103.4 512L103.4 212.9L196.3 212.9L196.3 512zM149.8 172.1C120.1 172.1 96 147.5 96 117.8C96 103.5 101.7 89.9 111.8 79.8C121.9 69.7 135.6 64 149.8 64C164 64 177.7 69.7 187.8 79.8C197.9 89.9 203.6 103.6 203.6 117.8C203.6 147.5 179.5 172.1 149.8 172.1zM543.9 512L451.2 512L451.2 366.4C451.2 331.7 450.5 287.2 402.9 287.2C354.6 287.2 347.2 324.9 347.2 363.9L347.2 512L254.4 512L254.4 212.9L343.5 212.9L343.5 253.7L344.8 253.7C357.2 230.2 387.5 205.4 432.7 205.4C526.7 205.4 544 267.3 544 347.7L544 512L543.9 512z"/></svg>',
	'email'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor" aria-hidden="true" focusable="false"><path d="M112 128C85.5 128 64 149.5 64 176C64 191.1 71.1 205.3 83.2 214.4L291.2 370.4C308.3 383.2 331.7 383.2 348.8 370.4L556.8 214.4C568.9 205.3 576 191.1 576 176C576 149.5 554.5 128 528 128L112 128zM64 260L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 260L377.6 408.8C343.5 434.4 296.5 434.4 262.4 408.8L64 260z"/></svg>',
	'phone'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor" aria-hidden="true" focusable="false"><path d="M224.2 89C216.3 70.1 195.7 60.1 176.1 65.4L170.6 66.9C106 84.5 50.8 147.1 66.9 223.3C104 398.3 241.7 536 416.7 573.1C493 589.3 555.5 534 573.1 469.4L574.6 463.9C580 444.2 569.9 423.6 551.1 415.8L453.8 375.3C437.3 368.4 418.2 373.2 406.8 387.1L368.2 434.3C297.9 399.4 241.3 341 208.8 269.3L253 233.3C266.9 222 271.6 202.9 264.8 186.3L224.2 89z"/></svg>',
];

// Values
$values_heading  = vc_heading_parts( 'ab_values_heading', false, 'What we <span>stand by</span>.' );
$values_fallback = [
	[ 'word' => 'Honesty', 'line' => 'We tell you what will work and what will not, before you spend a penny.' ],
	[ 'word' => 'Craft',   'line' => 'Design and build happen in-house, to a standard we are happy to put our name on.' ],
	[ 'word' => 'Proof',   'line' => 'Every project is measured by what it returns, and reported in plain English.' ],
	[ 'word' => 'Graft',   'line' => 'Hands-on and quick to move: no layers, no waiting, no excuses.' ],
];
$values = [];
if ( have_rows('ab_values') ) {
	while ( have_rows('ab_values') ) {
		the_row();
		$values[] = [ 'word' => get_sub_field('word'), 'line' => get_sub_field('line') ];
	}
}
$values = $values ?: $values_fallback;

// How we work
$how_heading  = vc_heading_parts( 'ab_how_heading', false, 'How we <span>work</span>.' );
$how_intro    = get_field('ab_how_intro') ?: 'No mystery and no theatre: this is what working with Vulkan looks like.';
$how_fallback = [
	[ 'title' => 'You talk to the founders',       'description' => 'Every call, plan and build decision involves one of us directly. Nothing is delegated to a team you have never met.' ],
	[ 'title' => 'One team under one roof',        'description' => 'Strategy, design, development, content, SEO and paid media sit together, so nothing gets lost between suppliers.' ],
	[ 'title' => 'In person where it counts',      'description' => 'We are London based and happy to sit around your table. Big decisions go better face to face.' ],
	[ 'title' => 'Measured and reported plainly',  'description' => 'You see what we did, what it cost and what it returned, in reports written in plain English.' ],
];
$how_items = [];
if ( have_rows('ab_how_items') ) {
	while ( have_rows('ab_how_items') ) {
		the_row();
		$how_items[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}
$how_items = $how_items ?: $how_fallback;

// Proof: testimonials from the CPT (the homepage spotlight component) plus
// the client logo marquee from Global Settings. The rating chip reads the
// shared Google reviews options via vc_google_reviews().
$proof_heading = vc_heading_parts( 'ab_proof_heading', false, "Don't take <span>our word</span> for it." );
$testimonial_posts = new WP_Query([
	'post_type'      => 'testimonial',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
]);
$testimonial_items = [];
if ( $testimonial_posts->have_posts() ) {
	while ( $testimonial_posts->have_posts() ) { $testimonial_posts->the_post();
		$tm_photo = get_field('tm_photo');
		$testimonial_items[] = [
			'quote'   => get_field('tm_quote'),
			'name'    => get_field('tm_name'),
			'company' => trim( get_field('tm_role') . ', ' . get_field('tm_company'), ', ' ),
			'photo'   => $tm_photo['sizes']['medium'] ?? $tm_photo['url'] ?? VC_TEMPLATE_URI . '/assets/images/testimonials/avatar-placeholder.webp',
		];
	}
	wp_reset_postdata();
}

// Story (migrated from the homepage)
$story_heading     = vc_heading_parts( 'ab_story_heading', false, 'Our <span>Story</span>' );
$story_description = get_field('ab_story_description') ?: "We started Vulkan because agency work had drifted: bloated teams, vague reports and clients kept at arm's length. We do it differently: in person, honest about what works and measured by what it returns. Press play for the story in our own words.";
$story_button      = get_field('ab_story_button_label') ?: 'Watch the Film';
$story_video       = get_field('ab_story_video_url') ?: 'https://vulkancreative.com/wp-content/VulkanTrailer.mp4';

// Press feature (As featured in). The raw parts gate the section: wiping every
// press field in admin removes it; the fallbacks only cover partial blanks.
$press_has_content = get_field('ab_press_heading_start') || get_field('ab_press_heading_red') || get_field('ab_press_heading_end') || get_field('ab_press_body');
$press_heading = vc_heading_parts( 'ab_press_heading', false, 'As featured in <span>Your Business</span> magazine.' );
$press_body    = get_field('ab_press_body') ?: 'The Spring 2026 issue of Your Business, the magazine fronted by James Caan CBE, carries a two-page feature on Vulkan Creative. It looks at why so much SME marketing underperforms, and how our in-house, end-to-end approach turns attention into consistent enquiries.';
$press_label   = get_field('ab_press_link_label') ?: 'Read the Feature';
$press_url     = get_field('ab_press_link_url') ?: 'https://europe.nxtbook.com/emp/AtHome/your-business-with-james-caan-spring-2026/index.php#/p/50';
$press_image   = get_field('ab_press_image');
if ( $press_image && ! is_array( $press_image ) && function_exists( 'acf_get_attachment' ) ) {
	$press_image = acf_get_attachment( (int) $press_image ) ?: null;
}
?>

<?php
// The shared page-hero band; 'about-hero' is the alias the pre-hide rules in
// _about-us.scss and about/reveal.js target.
get_template_part( 'template-parts/page', 'hero', [
	'heading'    => $hero_heading,
	'subheading' => $hero_subheading,
	'class'      => 'about-hero',
] );
?>

<section class="about-founders" id="founders">
	<div class="container px-4">
		<?php // The positioning statement shares the section head with the heading
		// (heading left, red-ruled lead right): they make one argument, and the
		// merged head keeps the page from stacking two lookalike bands. ?>
		<div class="row align-items-lg-end founders-head">
			<div class="col-lg-6">
				<div class="content">
					<h2><?php echo wp_kses_post( $founders_heading ); ?></h2>
				</div>
			</div>
			<div class="col-lg-5 offset-lg-1">
				<div class="intro-lead">
					<p class="intro-statement"><?php echo wp_kses_post( $intro_statement ); ?></p>
					<p class="intro-support"><?php echo esc_html( $intro_support ); ?></p>
				</div>
			</div>
		</div>
		<div class="founders-duo">
			<?php foreach ( $founders as $f_i => $founder ) :
				$first_name = trim( explode( ' ', trim( $founder['name'] ) )[0] );
				$bio_id     = 'founder-bio-' . ( $f_i + 1 );
				$photo_url  = $founder['photo'] ?: $founder_photo_fallback;
				$phone_href = $founder['phone'] ? preg_replace( '/[^0-9+]/', '', $founder['phone'] ) : '';
			?>
				<article class="founder-panel">
					<div class="founder-media">
						<?php // The caption announces the founder, so the image stays alt="". ?>
						<img src="<?php echo esc_url( $photo_url ); ?>" alt="" loading="lazy">
						<div class="founder-caption">
							<?php // Fixed-width inner so the text never re-wraps while the panel width tweens. ?>
							<div class="caption-inner">
								<h3 class="founder-name"><?php echo esc_html( $founder['name'] ); ?></h3>
								<p class="founder-role"><?php echo esc_html( $founder['role'] ); ?></p>
								<p class="founder-short"><?php echo esc_html( $founder['short_bio'] ); ?></p>
								<button class="founder-toggle" type="button" aria-expanded="true" aria-controls="<?php echo esc_attr( $bio_id ); ?>">
									<span class="toggle-glyph" aria-hidden="true"></span>
									<span class="toggle-label">Meet <?php echo esc_html( $first_name ); ?></span>
								</button>
							</div>
						</div>
						<?php // The narrow "closed tab" view a panel crossfades to while its sibling is open. ?>
						<div class="founder-spine">
							<p class="spine-name"><?php echo esc_html( $founder['name'] ); ?></p>
							<button class="founder-toggle founder-toggle--spine" type="button" aria-expanded="true" aria-controls="<?php echo esc_attr( $bio_id ); ?>" aria-label="Meet <?php echo esc_attr( $first_name ); ?>">
								<span class="toggle-glyph" aria-hidden="true"></span>
							</button>
						</div>
					</div>
					<div class="founder-bio" id="<?php echo esc_attr( $bio_id ); ?>">
						<div class="founder-bio-inner">
							<div class="founder-long"><?php echo wp_kses_post( $founder['long_bio'] ); ?></div>
							<?php // The shared contact-channel rows (common/_contact-channels.scss), same format and hover as the Contact page. ?>
							<ul class="contact-channels founder-channels">
								<?php if ( $founder['linkedin'] ) : ?>
									<li>
										<a class="channel" href="<?php echo esc_url( $founder['linkedin'] ); ?>" target="_blank" rel="noopener">
											<span class="channel-icon" aria-hidden="true"><?php echo $ab_icons['linkedin']; ?></span>
											<span class="channel-text">
												<span class="channel-label">LinkedIn</span>
												<span class="channel-value"><?php echo esc_html( $first_name ); ?> on LinkedIn</span>
											</span>
										</a>
									</li>
								<?php endif; ?>
								<?php if ( $founder['email'] ) : ?>
									<li>
										<a class="channel" href="mailto:<?php echo esc_attr( $founder['email'] ); ?>">
											<span class="channel-icon" aria-hidden="true"><?php echo $ab_icons['email']; ?></span>
											<span class="channel-text">
												<span class="channel-label">Email</span>
												<span class="channel-value"><?php echo esc_html( $founder['email'] ); ?></span>
											</span>
										</a>
									</li>
								<?php endif; ?>
								<?php if ( $founder['phone'] ) : ?>
									<li>
										<a class="channel" href="tel:<?php echo esc_attr( $phone_href ); ?>">
											<span class="channel-icon" aria-hidden="true"><?php echo $ab_icons['phone']; ?></span>
											<span class="channel-text">
												<span class="channel-label">Phone</span>
												<span class="channel-value"><?php echo esc_html( $founder['phone'] ); ?></span>
											</span>
										</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="about-story" id="story">
	<div class="container px-4">
		<?php // Heading and description stack left; the film CTA sits bottom-right
		// of the head row (Ibrar's call). ?>
		<div class="row align-items-lg-end story-head">
			<div class="col-lg-8">
				<div class="content">
					<h2><?php echo wp_kses_post( $story_heading ); ?></h2>
					<p class="split-text-story"><?php echo esc_html( $story_description ); ?></p>
				</div>
			</div>
			<div class="col-lg-4 story-cta">
				<div class="bottom"><a href="#watch" class="button"><?php echo esc_html( $story_button ); ?></a></div>
			</div>
		</div>
		<div class="video-wrapper" id="watch">
			<video
					id="our-story"
					class="video-js vjs-theme-city"
					controls
					preload="metadata"
					poster="<?php echo VC_TEMPLATE_URI . '/assets/images/hero/story-poster.webp'; ?>"
					title="Our story"
			>
				<source src="<?php echo esc_url( $story_video ); ?>" type="video/mp4" />
			</video>
		</div>
	</div>
</section>

<section class="about-values" id="values">
	<div class="container px-4">
		<div class="content">
			<h2><?php echo wp_kses_post( $values_heading ); ?></h2>
		</div>
		<div class="values-list">
			<?php foreach ( $values as $value ) :
				$word = trim( $value['word'] );
			?>
				<div class="value-row">
					<div class="row align-items-lg-end">
						<div class="col-lg-7">
							<p class="value-word"><?php echo esc_html( $word ); ?><span class="value-fill" aria-hidden="true"><?php echo esc_html( $word ); ?></span></p>
						</div>
						<div class="col-lg-4 offset-lg-1">
							<p class="value-line"><?php echo esc_html( $value['line'] ); ?></p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="about-how" id="how">
	<div class="container px-4">
		<div class="row">
			<div class="col-lg-4">
				<div class="content how-head">
					<h2><?php echo wp_kses_post( $how_heading ); ?></h2>
					<p class="sub-heading"><?php echo esc_html( $how_intro ); ?></p>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<div class="how-rail-wrap">
					<span class="how-progress" aria-hidden="true"></span>
					<ul class="how-rows">
						<?php foreach ( $how_items as $item ) : ?>
							<li class="how-row">
								<h3><?php echo esc_html( $item['title'] ); ?></h3>
								<p><?php echo esc_html( $item['description'] ); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="about-proof" id="proof">
	<div class="container px-4">
		<div class="proof-head">
			<div class="content">
				<h2><?php echo wp_kses_post( $proof_heading ); ?></h2>
			</div>
			<?php get_template_part( 'template-parts/rating-chip' ); ?>
		</div>
		<?php if ( $testimonial_items ) : ?>
			<div class="splide testimonial-spotlight" id="testimonial-splide" aria-label="Client testimonials">
				<div class="spotlight-layout">
					<div class="spotlight-photo" aria-hidden="true">
						<?php foreach ( $testimonial_items as $tm_i => $tm_item ) : ?>
							<img class="spotlight-portrait<?php echo $tm_i === 0 ? ' is-active' : ''; ?>" loading="lazy" src="<?php echo esc_url( $tm_item['photo'] ); ?>" alt="">
						<?php endforeach; ?>
					</div>
					<div class="spotlight-main">
						<div class="spotlight-mark" aria-hidden="true">“</div>
						<div class="splide__track">
							<ul class="splide__list">
								<?php foreach ( $testimonial_items as $tm_item ) : ?>
									<li class="splide__slide">
										<blockquote>
											<p class="spotlight-quote"><?php echo esc_html( $tm_item['quote'] ); ?></p>
											<cite>
												<span class="cite-avatar" aria-hidden="true">
													<img loading="lazy" src="<?php echo esc_url( $tm_item['photo'] ); ?>" alt="">
												</span>
												<span class="cite-text">
													<span class="t-name"><?php echo esc_html( $tm_item['name'] ); ?></span>
													<span class="t-company"><?php echo esc_html( $tm_item['company'] ); ?></span>
												</span>
											</cite>
										</blockquote>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="spotlight-footer">
							<div class="spotlight-progress" aria-hidden="true"><div class="spotlight-progress-bar"></div></div>
							<div class="spotlight-controls">
								<span class="spotlight-counter" aria-hidden="true"><span class="current">01</span> / <span class="total"><?php echo str_pad( count( $testimonial_items ), 2, '0', STR_PAD_LEFT ); ?></span></span>
								<div class="splide__arrows">
									<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous testimonial">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
									</button>
									<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next testimonial">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( have_rows( 'worked_with_logos', 'options' ) ) : ?>
			<div class="about-logos">
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

<?php if ( $press_has_content ) : ?>
<section class="about-press" id="press">
	<div class="container px-4">
		<div class="row align-items-lg-end press-head">
			<div class="col-lg-6">
				<div class="content">
					<h2><?php echo wp_kses_post( $press_heading ); ?></h2>
				</div>
			</div>
			<div class="col-lg-5 offset-lg-1 press-lead">
				<?php if ( $press_body ) : ?>
					<p class="press-body"><?php echo esc_html( $press_body ); ?></p>
				<?php endif; ?>
				<?php if ( $press_url && $press_label ) : ?>
					<a class="button press-cta" href="<?php echo esc_url( $press_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $press_label ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if ( $press_image ) :
		// The print run: the spread repeated as overlapping, fanned copies on
		// three slow rows (smaller and dimmer towards the back, the middle row
		// running the other way), tilted as one plane and cut by the band's
		// edges. Decorative: the head above carries the information, so the
		// rack is hidden from assistive tech. Each row renders its set twice
		// and the CSS sizes the track from the count, so the loop slides by
		// exactly one set; counts are sized so one set outspans the plane on
		// viewports up to 2560px wide.
		$rack_src = esc_url( $press_image['url'] );
		$rack_w   = (int) $press_image['width'];
		$rack_h   = (int) $press_image['height'];
		$rack_row = function ( $class, $count ) use ( $rack_src, $rack_w, $rack_h ) {
			echo '<div class="press-rack-row ' . esc_attr( $class ) . '" style="--set-count: ' . (int) $count . '"><div class="press-rack-track">';
			for ( $i = 0; $i < 2 * $count; $i++ ) {
				echo '<img class="press-print" src="' . $rack_src . '" alt="" width="' . $rack_w . '" height="' . $rack_h . '">';
			}
			echo '</div></div>';
		};
		?>
		<div class="press-rack" aria-hidden="true">
			<div class="press-rack-plane">
				<?php
				$rack_row( 'is-far', 26 );
				$rack_row( 'is-back', 19 );
				$rack_row( 'is-front', 14 );
				?>
			</div>
		</div>
	<?php endif; ?>
</section>
<?php endif; ?>

<?php
// Person nodes for the two founders, attached to Yoast's Organization node
// (verified: Yoast emits @id /#organization on this site), fed by the
// ab_founders repeater so the schema follows editor changes.
$vc_org_id    = home_url( '/#organization' );
$person_nodes = [];
foreach ( $founders as $founder ) {
	if ( empty( $founder['name'] ) ) {
		continue;
	}
	$person = [
		'@type'    => 'Person',
		'@id'      => get_permalink() . '#' . sanitize_title( $founder['name'] ),
		'name'     => $founder['name'],
		'jobTitle' => $founder['role'],
		'url'      => get_permalink(),
		'worksFor' => [ '@id' => $vc_org_id ],
	];
	if ( ! empty( $founder['photo'] ) ) {
		$person['image'] = $founder['photo'];
	}
	if ( ! empty( $founder['linkedin'] ) ) {
		$person['sameAs'] = [ $founder['linkedin'] ];
	}
	if ( ! empty( $founder['email'] ) ) {
		$person['email'] = 'mailto:' . $founder['email'];
	}
	if ( ! empty( $founder['phone'] ) ) {
		$person['telephone'] = $founder['phone'];
	}
	$person_nodes[] = $person;
}
if ( $person_nodes ) {
	echo '<script type="application/ld+json">'
		. wp_json_encode( [ '@context' => 'https://schema.org', '@graph' => $person_nodes ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>';
}

get_footer();
