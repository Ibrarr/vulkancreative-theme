<?php
get_header();

// Heading highlight defaults: map a plain-text default to a span-highlighted version.
// If the ACF value is empty or matches the plain default, use the highlighted version;
// otherwise the editor's own text is used as-is (they may include their own <span>).
$hp_heading_highlights = [
	'hp_results_heading'      => [ 'Results That Matter',                  'Results That <span>Matter</span>' ],
	'hp_services_heading'     => [ 'Built around one goal: your growth.',  'Built around one goal: <span>your growth</span>.' ],
	'hp_work_heading'         => [ 'Forged with our clients.',             'Forged with <span>our clients</span>.' ],
	'hp_why_heading'          => [ 'Why Choose Vulkan?',                   'Why Choose <span>Vulkan</span>?' ],
	'hp_story_heading'        => [ 'Our Story',                            'Our <span>Story</span>' ],
	'hp_process_heading'      => [ 'A Clear Path From Spark to Scale',     'A Clear Path From <span>Spark to Scale</span>' ],
	'hp_testimonials_heading' => [ 'Trusted by Ambitious Brands',          'Trusted by <span>Ambitious Brands</span>' ],
	'hp_contact_heading'      => [ 'Have a project you want to discuss?',  'Have a <span>project</span> you want to discuss?' ],
];
function hp_heading( $field_name, $highlights ) {
	$val = get_field( $field_name );
	if ( isset( $highlights[ $field_name ] ) ) {
		if ( ! $val || $val === $highlights[ $field_name ][0] ) {
			return $highlights[ $field_name ][1];
		}
	}
	return $val ?: '';
}

// Hero
$hero_subheading       = get_field('hp_hero_subheading') ?: 'Strategy, design, development and content that turn attention into customers. One team, in person, accountable for the results.';
$hero_button           = get_field('hp_hero_button_text') ?: 'Start a project';
$hero_secondary_button = get_field('hp_hero_secondary_button_text') ?: 'See the results';

// Results
$results_tag     = get_field('hp_results_tag') ?: 'By the numbers';
$results_heading = hp_heading('hp_results_heading', $hp_heading_highlights);

// Services
$services_tag         = get_field('hp_services_tag') ?: 'What we do';
$services_heading     = hp_heading('hp_services_heading', $hp_heading_highlights);
$services_description = get_field('hp_services_description') ?: 'Six services, one joined-up team. Pick what you need now and scale when you are ready.';

// Work
$work_tag     = get_field('hp_work_tag') ?: 'Recent work';
$work_heading = hp_heading('hp_work_heading', $hp_heading_highlights);

// Why
$why_tag        = get_field('hp_why_tag') ?: 'Why Vulkan';
$why_heading    = hp_heading('hp_why_heading', $hp_heading_highlights);
$why_subheading = get_field('hp_why_subheading') ?: 'A dedicated partner, not a distant supplier. Three things we never compromise on.';

$why_items_default = [
	[
		'title'       => 'Hands-On, In Person',
		'description' => 'We sit down with you and learn how the business actually runs. You deal with the people doing the work, not an account queue.',
		'proof'       => '10+ years combined experience',
	],
	[
		'title'       => 'Bespoke, Never Templated',
		'description' => 'We build every brand, website and campaign around your audience, from the ground up. Nothing off the shelf, nothing recycled.',
		'proof'       => '120+ bespoke projects delivered',
	],
	[
		'title'       => 'Results You Can Measure',
		'description' => 'We tie every engagement to numbers that matter: enquiries, rankings, revenue. You always know what is working and why.',
		'proof'       => '4.9 average client rating',
	],
];
$why_items = [];
if ( have_rows('hp_why_items') ) {
	while ( have_rows('hp_why_items') ) {
		the_row();
		$why_items[] = [
			'title'       => get_sub_field('title'),
			'description' => get_sub_field('description'),
			'proof'       => get_sub_field('proof'),
		];
	}
}
$why_items = array_slice( $why_items ?: $why_items_default, 0, 3 );

$why_stat_value = get_field('hp_why_stat_value') ?: '2.3x';
$why_stat_label = get_field('hp_why_stat_label') ?: 'Average lead growth across our clients. The number we hold ourselves to.';
$why_note_title = get_field('hp_why_note_title') ?: 'Not the cheapest. The most accountable.';
$why_note_text  = get_field('hp_why_note_text') ?: 'One partner answerable for strategy, design, build and growth. If something is not working, you hear it from us first, with a plan to fix it.';
$why_cta_text   = get_field('hp_why_cta_text') ?: 'Sound like your kind of partner?';
$why_cta_label  = get_field('hp_why_cta_label') ?: 'Start a project';

// Story
$story_tag         = get_field('hp_story_tag') ?: 'Inside the forge';
$story_heading     = hp_heading('hp_story_heading', $hp_heading_highlights);
$story_description = get_field('hp_story_description') ?: 'We built Vulkan to do agency work properly: in person, honest about what works and measured by what it returns. Press play to meet the team you would be working with.';

// Process
$process_tag         = get_field('hp_process_tag') ?: 'How we work';
$process_heading     = hp_heading('hp_process_heading', $hp_heading_highlights);
$process_description = get_field('hp_process_description') ?: 'A clear, collaborative process that takes you from first conversation to measurable results, with one partner accountable the whole way.';

// Testimonials
$testimonials_tag          = get_field('hp_testimonials_tag') ?: 'What clients say';
$testimonials_heading      = hp_heading('hp_testimonials_heading', $hp_heading_highlights);
$testimonials_rating_value = get_field('hp_testimonials_rating_value') ?: '4.9';
$testimonials_rating_label = get_field('hp_testimonials_rating_label') ?: 'average client rating';

// Contact
$contact_tag        = get_field('hp_contact_tag') ?: 'Let’s talk';
$contact_heading    = hp_heading('hp_contact_heading', $hp_heading_highlights);
$contact_subheading = get_field('hp_contact_subheading') ?: 'Tell us where you want to be. We will reply within one working day with a clear next step.';
?>

<section class="hero" id="top">
    <div class="hero-glow" aria-hidden="true"></div>
    <div class="graphic" aria-hidden="true"></div>
    <div class="hero-content-wrap">
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-9 col-xl-8 content">
                    <h1>
                        <span class="h1-line">We forge</span>
                        <span class="dynamic-text">
                            <span class="word">brands</span>
                            <span class="word">websites</span>
                            <span class="word">marketing</span>
                            <span class="word">content</span>
                        </span>
                        <span class="h1-line">built to perform<span class="red">.</span></span>
                    </h1>
                    <p class="split-text-hero"><?php echo esc_html( $hero_subheading ); ?></p>
                    <div class="bottom">
                        <a href="#contact" class="button"><?php echo esc_html( $hero_button ); ?></a>
                        <a href="#work" class="button-ghost"><?php echo esc_html( $hero_secondary_button ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-marquee">
        <div class="splide" id="logo-splide" aria-label="Companies we've worked with">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php while ( have_rows('worked_with_logos', 'options') ) : the_row();
                        $logo = get_sub_field('logo');
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
</section>

<section class="results" id="results">
    <div class="container px-4">
        <div class="content">
            <p class="tag"><?php echo esc_html( $results_tag ); ?></p>
            <h2><?php echo wp_kses_post( $results_heading ); ?></h2>
        </div>
        <div class="row gx-4 gy-4 stats-grid">
            <?php if ( have_rows('hp_results_stats') ) : ?>
                <?php while ( have_rows('hp_results_stats') ) : the_row(); ?>
                    <div class="col-lg-3 col-6">
                        <div class="stat">
                            <span class="stat-number"><?php echo esc_html( get_sub_field('value') ); ?></span>
                            <p class="stat-label"><?php echo esc_html( get_sub_field('label') ); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else :
                $placeholder_stats = [
                    [ '120+', 'Projects delivered' ],
                    [ '4.9',  'Average client rating' ],
                    [ '2.3x', 'Average lead growth' ],
                    [ '10+',  'Years combined experience' ],
                ];
                foreach ( $placeholder_stats as $stat ) : ?>
                    <div class="col-lg-3 col-6">
                        <div class="stat">
                            <span class="stat-number"><?php echo esc_html( $stat[0] ); ?></span>
                            <p class="stat-label"><?php echo esc_html( $stat[1] ); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="services" id="services">
    <div class="services-sticky">
    <div class="container px-4">
        <div class="row services-head">
            <div class="col-lg-7">
                <div class="content">
                    <p class="tag"><?php echo esc_html( $services_tag ); ?></p>
                    <h2><?php echo wp_kses_post( $services_heading ); ?></h2>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1 services-intro">
                <p class="sub-heading"><?php echo esc_html( $services_description ); ?></p>
                <a href="#contact" class="button">Start a project</a>
            </div>
        </div>
        <div class="service-rail">
            <div class="service-track">
            <?php
            $services = get_terms([
                'taxonomy' => 'service',
                'hide_empty' => false,
            ]);

            foreach ($services as $key => $service) {
                $services[$key]->order = get_field('order', 'service_' . $service->term_id);
            }

            usort($services, function ($a, $b) {
                return $a->order - $b->order;
            });

            $service_i = 1;
            foreach ($services as $service) {
                $title = $service->name;
                $description = wp_strip_all_tags( term_description($service->term_id, 'service') );
                $icon = get_field('icon', 'service_' . $service->term_id);
                $icon_url = trailingslashit( home_url('/wp-content/themes/vulkancreative-theme/assets/images/icons/services') ) . ltrim($icon, '/');
                ?>
                <a class="service-row" href="#contact" aria-label="Enquire about <?php echo esc_attr( $title ); ?>">
                    <img class="service-icon" loading="lazy" src="<?php echo esc_url( $icon_url ); ?>" alt="" aria-hidden="true">
                    <span class="service-index"><?php echo str_pad( $service_i, 2, '0', STR_PAD_LEFT ); ?></span>
                    <span class="service-main">
                        <span class="service-title-row">
                            <h3 class="service-title"><?php echo esc_html( $title ); ?></h3>
                        </span>
                        <span class="service-desc"><?php echo esc_html( $description ); ?></span>
                    </span>
                    <span class="service-arrow" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </span>
                </a>
                <?php
                $service_i++;
            }
            ?>
            </div>
        </div>
    </div>
    </div>
</section>

<section class="work" id="work">
    <div class="container px-4">
        <div class="content">
            <p class="tag"><?php echo esc_html( $work_tag ); ?></p>
            <h2><?php echo wp_kses_post( $work_heading ); ?></h2>
        </div>
        <?php
        $case_studies = new WP_Query([
            'post_type'      => 'case_study',
            'posts_per_page' => 3,
            'no_found_rows'  => true,
            'meta_key'       => 'cs_featured',
            'meta_value'     => '1',
        ]);
        if ( $case_studies->have_posts() ) :
            $work_cases = [];
            while ( $case_studies->have_posts() ) { $case_studies->the_post();
                $work_cases[] = [
                    'client'  => get_field('cs_client_name') ?: get_the_title(),
                    'sector'  => get_field('cs_sector'),
                    'summary' => get_field('cs_summary'),
                    'value'   => get_field('cs_metric_value'),
                    'label'   => get_field('cs_metric_label'),
                    'image'   => get_field('cs_image'),
                ];
            }
            wp_reset_postdata(); ?>
            <div class="work-showcase">
                <div class="case-list">
                    <?php foreach ( $work_cases as $work_i => $work_case ) : ?>
                        <a class="case-row<?php echo $work_i === 0 ? ' is-active' : ''; ?>" href="#contact" data-case="<?php echo (int) $work_i; ?>" aria-label="Discuss a project like <?php echo esc_attr( $work_case['client'] ); ?>">
                            <?php if ( $work_case['image'] ) : ?>
                                <span class="case-bg" aria-hidden="true">
                                    <img loading="lazy" src="<?php echo esc_url( $work_case['image']['sizes']['large'] ?? $work_case['image']['url'] ); ?>" alt="">
                                </span>
                            <?php endif; ?>
                            <span class="case-overlay">
                                <span class="case-index" aria-hidden="true"><?php echo esc_html( str_pad( $work_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
                                <span class="case-text">
                                    <?php if ( $work_case['sector'] ) : ?><span class="case-sector"><?php echo esc_html( $work_case['sector'] ); ?></span><?php endif; ?>
                                    <h3 class="case-client"><?php echo esc_html( $work_case['client'] ); ?></h3>
                                    <span class="case-summary"><?php echo esc_html( $work_case['summary'] ); ?></span>
                                </span>
                                <span class="case-metric">
                                    <span class="metric-value"><?php echo esc_html( $work_case['value'] ); ?></span>
                                    <span class="metric-label"><?php echo esc_html( $work_case['label'] ); ?></span>
                                </span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="case-stage" aria-hidden="true">
                    <?php foreach ( $work_cases as $work_i => $work_case ) : ?>
                        <?php if ( $work_case['image'] ) : ?>
                            <img class="stage-img<?php echo $work_i === 0 ? ' is-active' : ''; ?>" data-case="<?php echo (int) $work_i; ?>" loading="lazy" src="<?php echo esc_url( $work_case['image']['sizes']['large'] ?? $work_case['image']['url'] ); ?>" alt="">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <p class="work-outro">Your project could be next. <a href="#contact">Start a project</a></p>
        <?php endif; ?>
    </div>
</section>

<section class="why" id="why">
    <div class="container px-4">
        <div class="content">
            <p class="tag"><?php echo esc_html( $why_tag ); ?></p>
            <h2><?php echo wp_kses_post( $why_heading ); ?></h2>
            <p class="sub-heading"><?php echo esc_html( $why_subheading ); ?></p>
        </div>
        <div class="why-grid">
            <?php $why_i = 1; foreach ( $why_items as $why_item ) : ?>
                <div class="why-cell why-cell-diff why-cell-diff-<?php echo (int) $why_i; ?>">
                    <span class="why-index" aria-hidden="true"><?php echo esc_html( str_pad( $why_i, 2, '0', STR_PAD_LEFT ) ); ?></span>
                    <h3><?php echo esc_html( $why_item['title'] ); ?></h3>
                    <p><?php echo esc_html( $why_item['description'] ); ?></p>
                    <?php if ( ! empty( $why_item['proof'] ) ) : ?>
                        <span class="why-proof"><?php echo esc_html( $why_item['proof'] ); ?></span>
                    <?php endif; ?>
                </div>
            <?php $why_i++; endforeach; ?>
            <div class="why-cell why-cell-stat">
                <span class="why-stat-value"><?php echo esc_html( $why_stat_value ); ?></span>
                <p class="why-stat-label"><?php echo esc_html( $why_stat_label ); ?></p>
            </div>
            <div class="why-cell why-cell-note">
                <h3><?php echo esc_html( $why_note_title ); ?></h3>
                <p><?php echo esc_html( $why_note_text ); ?></p>
            </div>
            <div class="why-cell why-cell-cta">
                <p class="why-cta-text"><?php echo esc_html( $why_cta_text ); ?></p>
                <a class="button" href="#contact"><?php echo esc_html( $why_cta_label ); ?></a>
            </div>
        </div>
    </div>
</section>

<section class="story" id="story">
    <div class="container px-4">
        <div class="row story-head">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag"><?php echo esc_html( $story_tag ); ?></p>
                    <h2><?php echo wp_kses_post( $story_heading ); ?></h2>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 story-intro">
                <p class="split-text-story"><?php echo esc_html( $story_description ); ?></p>
                <div class="bottom"><a href="#watch" class="button">Watch the film</a></div>
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
                <source src="https://vulkancreative.com/wp-content/VulkanTrailer.mp4" type="video/mp4" />
            </video>
        </div>
    </div>
</section>

<div class="text-marquee" aria-hidden="true">
    <div class="text-marquee-track">
        <span class="text-marquee-row">Strategy<em>&bull;</em>Design<em>&bull;</em>Development<em>&bull;</em>Content<em>&bull;</em>SEO<em>&bull;</em>Paid media<em>&bull;</em></span>
        <span class="text-marquee-row">Strategy<em>&bull;</em>Design<em>&bull;</em>Development<em>&bull;</em>Content<em>&bull;</em>SEO<em>&bull;</em>Paid media<em>&bull;</em></span>
    </div>
</div>

<section class="process" id="process">
    <div class="container px-4">
        <div class="content">
            <p class="tag"><?php echo esc_html( $process_tag ); ?></p>
            <h2><?php echo wp_kses_post( $process_heading ); ?></h2>
            <p class="sub-heading"><?php echo esc_html( $process_description ); ?></p>
        </div>
        <div class="process-steps">
            <div class="process-progress" aria-hidden="true"></div>
            <?php if ( have_rows('hp_process_steps') ) : ?>
                <?php $step_i = 1; while ( have_rows('hp_process_steps') ) : the_row(); ?>
                    <div class="process-step">
                        <span class="step-number"><?php echo str_pad( $step_i, 2, '0', STR_PAD_LEFT ); ?></span>
                        <div class="step-content">
                            <h3><?php echo esc_html( get_sub_field('title') ); ?></h3>
                            <p><?php echo esc_html( get_sub_field('description') ); ?></p>
                        </div>
                    </div>
                    <?php $step_i++; endwhile; ?>
            <?php else :
                $placeholder_steps = [
                    [ 'Discover',  'We get to know your business, your customers and your goals, and audit where you are now.' ],
                    [ 'Strategy',  'We set the plan: positioning, priorities and the channels that will actually move the needle.' ],
                    [ 'Build',     'We design and develop the brand, website and campaigns, built bespoke around your audience.' ],
                    [ 'Optimise',  'We measure what matters and refine continuously, so results compound over time.' ],
                ];
                $step_i = 1;
                foreach ( $placeholder_steps as $step ) : ?>
                    <div class="process-step">
                        <span class="step-number"><?php echo str_pad( $step_i, 2, '0', STR_PAD_LEFT ); ?></span>
                        <div class="step-content">
                            <h3><?php echo esc_html( $step[0] ); ?></h3>
                            <p><?php echo esc_html( $step[1] ); ?></p>
                        </div>
                    </div>
                    <?php $step_i++; endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="testimonials" id="testimonials">
    <div class="container px-4">
        <div class="testimonials-head">
            <div class="content">
                <p class="tag"><?php echo esc_html( $testimonials_tag ); ?></p>
                <h2><?php echo wp_kses_post( $testimonials_heading ); ?></h2>
            </div>
            <div class="rating-chip">
                <span class="rating-stars" aria-hidden="true">
                    <?php for ( $star_i = 0; $star_i < 5; $star_i++ ) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 2l2.92 6.26 6.83.62-5.17 4.56 1.54 6.7L12 16.67 5.88 20.14l1.54-6.7L2.25 8.88l6.83-.62L12 2z"/></svg>
                    <?php endfor; ?>
                </span>
                <span class="rating-text"><span class="rating-value"><?php echo esc_html( $testimonials_rating_value ); ?></span> <?php echo esc_html( $testimonials_rating_label ); ?></span>
            </div>
        </div>
        <?php if ( have_rows('hp_testimonials_logos') ) : ?>
            <div class="trust-logos">
                <?php while ( have_rows('hp_testimonials_logos') ) : the_row();
                    $logo = get_sub_field('logo');
                    $alt = get_sub_field('alt_text');
                    if ( $logo ) : ?>
                        <img loading="lazy" src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $alt ?: $logo['alt'] ?: $logo['title'] ); ?>" class="trust-logo">
                    <?php endif;
                endwhile; ?>
            </div>
        <?php endif; ?>
        <?php
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
        if ( $testimonial_items ) : ?>
            <div class="splide testimonial-spotlight" id="testimonial-splide" aria-label="Client testimonials">
                <div class="spotlight-layout">
                    <div class="spotlight-photo" aria-hidden="true">
                        <?php foreach ( $testimonial_items as $tm_i => $tm_item ) : ?>
                            <img class="spotlight-portrait<?php echo $tm_i === 0 ? ' is-active' : ''; ?>" loading="lazy" src="<?php echo esc_url( $tm_item['photo'] ); ?>" alt="">
                        <?php endforeach; ?>
                    </div>
                    <div class="spotlight-main">
                        <div class="spotlight-mark" aria-hidden="true">&ldquo;</div>
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php foreach ( $testimonial_items as $tm_item ) : ?>
                                    <li class="splide__slide">
                                        <blockquote>
                                            <p class="spotlight-quote"><?php echo esc_html( $tm_item['quote'] ); ?></p>
                                            <cite>
                                                <span class="t-name"><?php echo esc_html( $tm_item['name'] ); ?></span>
                                                <span class="t-company"><?php echo esc_html( $tm_item['company'] ); ?></span>
                                            </cite>
                                        </blockquote>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="spotlight-footer">
                            <div class="spotlight-progress" aria-hidden="true"><div class="spotlight-progress-bar"></div></div>
                            <div class="spotlight-controls">
                                <span class="spotlight-counter" aria-hidden="true"><span class="current">01</span>&nbsp;/&nbsp;<span class="total"><?php echo str_pad( count( $testimonial_items ), 2, '0', STR_PAD_LEFT ); ?></span></span>
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
    </div>
</section>

<section class="contact" id="contact">
    <div class="container px-4">
        <div class="row gx-5">
            <div class="col-lg-5">
                <div class="content">
                    <p class="tag"><?php echo esc_html( $contact_tag ); ?></p>
                    <h2 class="split-text-contact"><?php echo wp_kses_post( $contact_heading ); ?></h2>
                    <p class="sub-heading"><?php echo esc_html( $contact_subheading ); ?></p>
                </div>
            </div>
            <div class="col-lg-7 form">
                <div class="form-container">
                    <?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>
