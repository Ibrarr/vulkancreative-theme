<?php
/*
Template Name: Free Website
*/
get_header();

// Standalone offer landing page: slim header (see header.php), footer CTA row
// suppressed (see footer.php). Headings are composed from three editable parts
// by vc_heading_parts(); fallbacks only apply when all three parts are blank.

// Hero
$hero_heading         = vc_heading_parts( 'fw_hero_heading', false, 'Your new website. <span>Built free</span>.' );
$hero_subheading      = get_field('fw_hero_subheading') ?: 'For local businesses that want a proper website without paying thousands up front. We design it, build it, host it and look after it. You pay from £49 a month, and you can leave whenever you like.';
$hero_primary_label   = get_field('fw_hero_primary_label') ?: 'Get Your Free Website';
$hero_secondary_label = get_field('fw_hero_secondary_label') ?: 'See How It Works';
$hero_note            = get_field('fw_hero_note') ?: 'No upfront cost · From £49 a month · No minimum term';

// How it works
$how_heading    = vc_heading_parts( 'fw_how_heading', false, 'How it <span>works</span>.' );
$how_subheading = get_field('fw_how_subheading') ?: 'The whole model in three steps. We make our money on the monthly fee, not the build, so there is nothing to hide.';
$how_steps_default = [
	[ 'title' => 'Tell us about your business',  'description' => 'We start with a short conversation. What you do, who your customers are and what you need the site to do. No jargon and no obligation.' ],
	[ 'title' => 'We build your website, free',  'description' => 'We design and write a professional site around your business. You see it, you ask for changes, and nothing goes live until you are happy.' ],
	[ 'title' => 'We host it and look after it', 'description' => 'From £49 a month we keep your site online, secure and up to date, and make small changes when you need them. One predictable cost, and you can cancel any time.' ],
];
$how_steps = [];
if ( have_rows('fw_how_steps') ) {
	while ( have_rows('fw_how_steps') ) {
		the_row();
		$how_steps[] = [ 'title' => get_sub_field('title'), 'description' => get_sub_field('description') ];
	}
}
$how_steps = $how_steps ?: $how_steps_default;

// Ledger
$ledger_heading      = vc_heading_parts( 'fw_ledger_heading', false, 'What you get for <span>£0 up front</span>.' );
$ledger_subheading   = get_field('fw_ledger_subheading') ?: 'Everything below is part of the deal. No extras to buy, no setup fee, no surprise invoices.';
$ledger_included     = get_field('fw_ledger_included_label') ?: 'Included';
$ledger_total_label  = get_field('fw_ledger_total_label') ?: 'You pay today:';
$ledger_total_value  = get_field('fw_ledger_total_value') ?: '£0';
$ledger_monthly_note = get_field('fw_ledger_monthly_note') ?: 'Then from £49 a month for everything above. No minimum term, cancel any time.';
$ledger_items_default = [
	[ 'item' => 'Your website, designed and built', 'detail' => 'Designed and written around your business and your customers, ready to bring in enquiries.' ],
	[ 'item' => 'Hosting, domain and SSL',          'detail' => 'Fast, secure hosting with your domain and certificate handled for you.' ],
	[ 'item' => 'Updates, security and backups',    'detail' => 'Software kept current, monitored for problems and backed up.' ],
	[ 'item' => 'Support when you need it',         'detail' => 'A real person to email or call. No ticket queues, no call centres.' ],
	[ 'item' => 'Small changes each month',         'detail' => 'New photos, price updates, opening hours, a new offer. Send it over and we handle it.' ],
];
$ledger_items = [];
if ( have_rows('fw_ledger_items') ) {
	while ( have_rows('fw_ledger_items') ) {
		the_row();
		$ledger_items[] = [ 'item' => get_sub_field('item'), 'detail' => get_sub_field('detail') ];
	}
}
$ledger_items = $ledger_items ?: $ledger_items_default;

// Contrast
$contrast_heading     = vc_heading_parts( 'fw_contrast_heading', false, "Most agencies charge <span>thousands</span>. We don't." );
$contrast_note        = get_field('fw_contrast_note') ?: 'Why free? Because we earn our keep monthly. We only do well if your website is good and you stay, so it is in our interest to build something that works and keep it working.';
$contrast_usual_title = get_field('fw_contrast_usual_title') ?: 'The usual way';
$contrast_this_title  = get_field('fw_contrast_this_title') ?: 'The Vulkan way';
$contrast_rows_default = [
	[ 'usual' => 'Thousands up front before you see a thing.', 'this' => '£0 up front. The build costs you nothing.' ],
	[ 'usual' => 'Hosting, security and updates cost extra.',  'this' => 'Hosting, security and updates are in one monthly fee.' ],
	[ 'usual' => 'Something breaks? That is another invoice.', 'this' => 'Something breaks? We fix it. It is covered.' ],
	[ 'usual' => 'Long contracts and tie-ins are common.',     'this' => 'No minimum term. Leave whenever you like.' ],
];
$contrast_rows = [];
if ( have_rows('fw_contrast_rows') ) {
	while ( have_rows('fw_contrast_rows') ) {
		the_row();
		$contrast_rows[] = [ 'usual' => get_sub_field('usual'), 'this' => get_sub_field('this') ];
	}
}
$contrast_rows = $contrast_rows ?: $contrast_rows_default;

// Proof: testimonials from the CPT (the shared spotlight component, About pattern).
$proof_heading     = vc_heading_parts( 'fw_proof_heading', false, 'What our clients <span>say</span>.' );
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
			'photo'   => $tm_photo['sizes']['medium'] ?? $tm_photo['url'] ?? VC_TEMPLATE_URI . '/assets/images/testimonials/avatar-placeholder.png',
		];
	}
	wp_reset_postdata();
}

// FAQ
$faq_heading = vc_heading_parts( 'fw_faq_heading', false, '<span>FAQs</span>.' );
$faqs_default = [
	[ 'question' => 'Why is the website free?', 'answer' => 'Because we make our money on the monthly fee, not the build. We only do well if your website is good and you stay with us, so we have every reason to build you something that works and to keep it working. There is no catch to find.' ],
	[ 'question' => 'What does the monthly fee cover?', 'answer' => 'Everything your website needs to run: hosting, your domain and SSL certificate, software updates, security monitoring, backups, support from a real person, and small content changes like new photos, prices or opening hours.' ],
	[ 'question' => 'How much is the monthly fee exactly?', 'answer' => 'It starts at £49 a month. The exact figure depends on the size of your site and what you need from it, and we agree it with you in writing before any work starts. It never changes without a conversation first.' ],
	[ 'question' => 'Is there a minimum term?', 'answer' => 'No. The fee is rolling monthly and you can cancel whenever you like. We would rather keep you by doing good work than by locking you in.' ],
	[ 'question' => 'What happens if I want to leave?', 'answer' => 'Leaving is simple and there is no long contract holding you here. Talk to us and we will agree the practical details with you, like what happens with your domain and content.' ],
	[ 'question' => 'How long does the build take?', 'answer' => 'It depends on the size of the site, but most are ready within a few weeks of our first conversation. We agree a timeline with you up front and keep you posted as we go.' ],
	[ 'question' => 'Do I need to write the words or supply photos?', 'answer' => 'No. We write the copy for you and help with imagery. If you have photos of your work, your team or your premises, even better; real photos of your business always beat stock images.' ],
	[ 'question' => 'Who is this for?', 'answer' => 'Local businesses that want a proper website without a big upfront cost: trades, cafés and restaurants, salons and clinics, accountants and other professional services. If customers look for you online, this is for you.' ],
];
$faqs = [];
if ( have_rows('fw_faqs') ) {
	while ( have_rows('fw_faqs') ) {
		the_row();
		$faqs[] = [ 'question' => get_sub_field('question'), 'answer' => get_sub_field('answer') ];
	}
}
$faqs = $faqs ?: $faqs_default;

// Enquire
$enquire_heading    = vc_heading_parts( 'fw_enquire_heading', false, 'Get your <span>free website</span>.' );
$enquire_subheading = get_field('fw_enquire_subheading') ?: 'Tell us who you are and we will come back to you within one working day. No obligation, no pressure, no jargon.';
$enquire_note       = get_field('fw_enquire_note') ?: '';

// Sticky bar
$sticky_label = get_field('fw_sticky_label') ?: 'Get Your Free Website';
?>

<section class="fw-hero" id="top">
	<div class="fw-hero-glow" aria-hidden="true"></div>
	<div class="fw-hero-mark" aria-hidden="true">£0</div>
	<div class="container px-4">
		<div class="row">
			<div class="col-lg-9 content">
				<h1><?php echo wp_kses_post( $hero_heading ); ?></h1>
				<p class="sub-heading"><?php echo esc_html( $hero_subheading ); ?></p>
				<div class="hero-actions">
					<a class="button" href="#enquire"><?php echo esc_html( $hero_primary_label ); ?></a>
					<a class="button-ghost" href="#how"><?php echo esc_html( $hero_secondary_label ); ?></a>
				</div>
				<?php if ( $hero_note ) : ?>
					<p class="hero-note"><?php echo esc_html( $hero_note ); ?></p>
					<span class="hero-rule" aria-hidden="true"></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section class="fw-how" id="how">
	<div class="container px-4">
		<div class="row fw-how-head">
			<div class="col-lg-7">
				<div class="content">
					<h2><?php echo wp_kses_post( $how_heading ); ?></h2>
				</div>
			</div>
			<div class="col-lg-4 offset-lg-1 fw-how-intro">
				<p class="sub-heading"><?php echo esc_html( $how_subheading ); ?></p>
			</div>
		</div>
		<div class="fw-how-rail-wrap">
			<span class="fw-how-rail" aria-hidden="true"></span>
			<ol class="fw-how-steps">
				<?php foreach ( $how_steps as $i => $step ) : ?>
					<li class="how-step">
						<span class="step-index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3 class="step-title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="step-desc"><?php echo esc_html( $step['description'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>

<section class="fw-ledger">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-4">
				<div class="fw-ledger-head">
					<div class="content">
						<h2><?php echo wp_kses_post( $ledger_heading ); ?></h2>
					</div>
					<p class="sub-heading"><?php echo esc_html( $ledger_subheading ); ?></p>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<div class="fw-ledger-plate">
					<ul class="ledger-list">
						<?php foreach ( $ledger_items as $row ) : ?>
							<li class="ledger-row">
								<span class="item-name"><?php echo esc_html( $row['item'] ); ?></span>
								<span class="ledger-stamp"><?php echo esc_html( $ledger_included ); ?></span>
								<?php if ( ! empty( $row['detail'] ) ) : ?>
									<span class="item-detail"><?php echo esc_html( $row['detail'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
					<div class="ledger-total">
						<span class="total-label"><?php echo esc_html( $ledger_total_label ); ?></span>
						<span class="total-value"><?php echo esc_html( $ledger_total_value ); ?></span>
					</div>
					<p class="ledger-note"><?php echo esc_html( $ledger_monthly_note ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="fw-contrast">
	<div class="container px-4">
		<div class="row">
			<div class="col-lg-7">
				<div class="content">
					<h2><?php echo wp_kses_post( $contrast_heading ); ?></h2>
				</div>
			</div>
			<div class="col-lg-4 offset-lg-1 fw-contrast-intro">
				<p class="sub-heading"><?php echo esc_html( $contrast_note ); ?></p>
			</div>
		</div>
		<?php // Clean two-column comparison: the usual way muted on the left, the Vulkan way in red and bold on the right. ?>
		<div class="contrast-compare">
			<div class="compare-row compare-row--head">
				<span class="cr-head cr-head--usual"><?php echo esc_html( $contrast_usual_title ); ?></span>
				<span class="cr-head cr-head--vulkan"><?php echo esc_html( $contrast_this_title ); ?></span>
			</div>
			<?php foreach ( $contrast_rows as $row ) : ?>
				<div class="compare-row">
					<span class="cr-usual"><?php echo esc_html( $row['usual'] ); ?></span>
					<span class="cr-vulkan"><?php echo esc_html( $row['this'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( $testimonial_items ) : ?>
<section class="fw-proof">
	<div class="container px-4">
		<div class="content">
			<h2><?php echo wp_kses_post( $proof_heading ); ?></h2>
		</div>
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
	</div>
</section>
<?php endif; ?>

<section class="fw-faq">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-4">
				<div class="fw-faq-head">
					<div class="content">
						<h2><?php echo wp_kses_post( $faq_heading ); ?></h2>
					</div>
					<a class="button-ghost" href="#enquire"><?php echo esc_html( $hero_primary_label ); ?></a>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<div class="fw-faq-list">
					<?php foreach ( $faqs as $i => $faq ) :
						$q_id = 'fw-faq-q-' . ( $i + 1 );
						$a_id = 'fw-faq-a-' . ( $i + 1 );
						?>
						<div class="fw-faq-item">
							<h3 class="faq-q">
								<button type="button" class="faq-toggle" id="<?php echo esc_attr( $q_id ); ?>" aria-expanded="true" aria-controls="<?php echo esc_attr( $a_id ); ?>">
									<span class="faq-q-text"><?php echo esc_html( $faq['question'] ); ?></span>
									<span class="faq-icon" aria-hidden="true"></span>
								</button>
							</h3>
							<div class="faq-a" id="<?php echo esc_attr( $a_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $q_id ); ?>">
								<div class="faq-a-inner"><?php echo wpautop( esc_html( $faq['answer'] ) ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="fw-enquire" id="enquire">
	<div class="fw-enquire-glow" aria-hidden="true"></div>
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-5 fw-enquire-intro">
				<div class="content">
					<h2><?php echo wp_kses_post( $enquire_heading ); ?></h2>
				</div>
				<p class="sub-heading"><?php echo esc_html( $enquire_subheading ); ?></p>
			</div>
			<div class="col-lg-6 offset-lg-1 fw-enquire-form form">
				<div class="form-container">
					<?php vc_render_form( 7 ); ?>
					<?php if ( $enquire_note ) : ?>
						<p class="enquire-note"><?php echo esc_html( $enquire_note ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="fw-sticky-cta">
	<a class="button" href="#enquire"><?php echo esc_html( $sticky_label ); ?></a>
</div>

<?php
// FAQPage structured data from the same repeater that renders the FAQ section.
$faq_schema = [
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => [],
];
foreach ( $faqs as $faq ) {
	$q = trim( wp_strip_all_tags( (string) $faq['question'] ) );
	$a = trim( wp_strip_all_tags( (string) $faq['answer'] ) );
	if ( '' === $q || '' === $a ) {
		continue;
	}
	$faq_schema['mainEntity'][] = [
		'@type'          => 'Question',
		'name'           => $q,
		'acceptedAnswer' => [
			'@type' => 'Answer',
			'text'  => $a,
		],
	];
}
if ( $faq_schema['mainEntity'] ) {
	echo '<script type="application/ld+json">'
		. wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>' . "\n";
}

// Service node for the offer, attached to Yoast's Organization (@id verified
// on this site; inc/filters.php enriches the same node). The Offer carries the
// free build (price 0) with the monthly fee as its recurring price component.
$service_schema = [
	'@context'    => 'https://schema.org',
	'@type'       => 'Service',
	'@id'         => get_permalink() . '#service',
	'name'        => 'Free website design and build for local businesses',
	'serviceType' => 'Website design and development',
	'description' => 'A professional website designed and built free for local businesses. One monthly fee from £49 covers hosting, domain and SSL, software updates, security, backups, support and small content changes. No upfront cost and no minimum term.',
	'url'         => get_permalink(),
	'provider'    => [ '@id' => home_url( '/#organization' ) ],
	'areaServed'  => [
		'@type' => 'Place',
		'name'  => 'London and the United Kingdom',
	],
	'audience'    => [
		'@type' => 'Audience',
		'name'  => 'Local businesses',
	],
	'offers'      => [
		'@type'              => 'Offer',
		'name'               => 'Free website build with monthly hosting and care',
		'description'        => 'The website build is free. Hosting and upkeep are covered by a rolling monthly fee from £49, with no minimum term.',
		'price'              => '0',
		'priceCurrency'      => 'GBP',
		'priceSpecification' => [
			'@type'             => 'UnitPriceSpecification',
			'minPrice'          => 49,
			'priceCurrency'     => 'GBP',
			'referenceQuantity' => [
				'@type'    => 'QuantitativeValue',
				'value'    => 1,
				'unitCode' => 'MON',
			],
		],
		'availability'       => 'https://schema.org/InStock',
	],
];
echo '<script type="application/ld+json">'
	. wp_json_encode( $service_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	. '</script>' . "\n";
?>

<?php
get_footer();
