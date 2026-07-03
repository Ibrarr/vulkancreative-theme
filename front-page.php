<?php
get_header();

// Section headings are composed from three editable parts (start, red, end)
// by vc_heading_parts() in inc/template-functions.php; the red part renders
// as the standard <span> highlight. The fallback strings below only apply
// when all three parts are blank.

// Hero
$hero_subheading       = get_field('hp_hero_subheading') ?: 'Strategy, design, development and content that turn attention into customers. One team, in person, accountable for the results.';
$hero_button           = get_field('hp_hero_button_text') ?: 'Start a Project';
$hero_secondary_button = get_field('hp_hero_secondary_button_text') ?: 'See the Results';

// Results
$results_heading = vc_heading_parts( 'hp_results_heading', false, 'Results That <span>Matter</span>' );

// Services
$services_heading     = vc_heading_parts( 'hp_services_heading', false, 'Built around one goal: <span>your growth</span>.' );
$services_description = get_field('hp_services_description') ?: 'Six services, one joined-up team. Pick what you need now and scale when you are ready.';

// Work
$work_heading = vc_heading_parts( 'hp_work_heading', false, 'Forged with <span>our clients</span>.' );

// Our Work
$our_work_heading    = vc_heading_parts( 'hp_our_work_heading', false, 'More of <span>our work</span>.' );
$our_work_subheading = get_field('hp_our_work_subheading') ?: 'Not every project gets the full story. Here is a wider cut of the brands, websites and campaigns that leave the forge.';

// Why
$why_heading    = vc_heading_parts( 'hp_why_heading', false, 'Why Choose <span>Vulkan</span>?' );
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
$why_cta_label  = get_field('hp_why_cta_label') ?: 'Start a Project';

// Process
$process_heading     = vc_heading_parts( 'hp_process_heading', false, 'A Clear Path From <span>Spark to Scale</span>' );
$process_description = get_field('hp_process_description') ?: 'A clear, collaborative process that takes you from first conversation to measurable results, with one partner accountable the whole way.';

// Testimonials
$testimonials_heading      = vc_heading_parts( 'hp_testimonials_heading', false, 'Trusted by <span>Ambitious Brands</span>' );
$testimonials_rating_value = get_field('hp_testimonials_rating_value') ?: '4.9';
$testimonials_rating_label = get_field('hp_testimonials_rating_label') ?: 'average client rating';

// Contact
$contact_heading    = vc_heading_parts( 'hp_contact_heading', false, 'Have a <span>project</span> you&nbsp;want to&nbsp;discuss?' );
// Non-breaking spaces keep "you want" and "to discuss" together once the
// SplitText reveal reverts (the nbsp only holds after the revert, not during
// the split). The fallback above already carries them, so this is a no-op there.
$contact_heading    = str_replace( array( 'you want', 'to discuss' ), array( 'you&nbsp;want', 'to&nbsp;discuss' ), $contact_heading );
$contact_subheading = get_field('hp_contact_subheading') ?: 'Tell us where you want to be. We will reply within one working day with a clear next step.';

// Latest insights
$latest_heading    = vc_heading_parts( 'hp_latest_heading', false, 'Latest <span>insights</span>.' );
$latest_subheading = get_field('hp_latest_subheading') ?: 'Fresh thinking on brand, web and marketing — what we’re learning, building and watching.';
$latest_cta_label  = get_field('hp_latest_cta_label') ?: 'View All Insights';
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
                        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button"><?php echo esc_html( $hero_button ); ?></a>
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
                    <h2><?php echo wp_kses_post( $services_heading ); ?></h2>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1 services-intro">
                <p class="sub-heading"><?php echo esc_html( $services_description ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button">Start a Project</a>
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
                <a class="service-row" href="<?php echo esc_url( get_term_link( $service ) ); ?>" aria-label="Explore <?php echo esc_attr( $title ); ?>">
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
            <p class="work-outro">Your project could be next. <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a></p>
        <?php endif; ?>
    </div>
</section>

<section class="our-work" id="our-work">
    <div class="container px-4">
        <?php
        // Curated on the homepage: the hp_our_work_projects relationship field
        // sets both the selection and the order of the shelf.
        $our_work_ids  = get_field('hp_our_work_projects');
        $work_projects = [];
        if ( $our_work_ids ) {
            foreach ( array_slice( (array) $our_work_ids, 0, 8 ) as $project_id ) {
                $project_image = get_field('pj_image', $project_id);
                if ( empty( $project_image ) ) { continue; }

                // Service label: Yoast's primary term wins, then the first assigned term.
                $service_label = '';
                if ( function_exists('yoast_get_primary_term_id') ) {
                    $primary_id = yoast_get_primary_term_id( 'service', $project_id );
                    if ( $primary_id ) {
                        $primary_term = get_term( $primary_id, 'service' );
                        if ( $primary_term && ! is_wp_error( $primary_term ) ) {
                            $service_label = $primary_term->name;
                        }
                    }
                }
                if ( ! $service_label ) {
                    $project_terms = get_the_terms( $project_id, 'service' );
                    if ( $project_terms && ! is_wp_error( $project_terms ) ) {
                        $service_label = $project_terms[0]->name;
                    }
                }

                $work_projects[] = [
                    'client'      => get_field('pj_client_name', $project_id) ?: get_the_title( $project_id ),
                    'sector'      => get_field('pj_sector', $project_id),
                    'description' => get_field('pj_description', $project_id),
                    'image'       => $project_image,
                    'link'        => get_field('pj_link', $project_id),
                    'service'     => $service_label,
                ];
            }
        }

        // The wheel itself is the shared template part (centre-out slotting
        // included), also used by the service pages' recent work section.
        get_template_part( 'template-parts/work-wheel', null, [
            'projects'    => $work_projects,
            'heading'     => $our_work_heading,
            'subheading'  => $our_work_subheading,
            'stage_label' => 'Selected projects',
        ] );
        ?>
    </div>
</section>

<section class="why" id="why">
    <div class="container px-4">
        <div class="content">
            <h2><?php echo wp_kses_post( $why_heading ); ?></h2>
            <p class="sub-heading"><?php echo esc_html( $why_subheading ); ?></p>
        </div>
        <div class="why-grid">
            <?php $why_i = 1; foreach ( $why_items as $why_item ) : ?>
                <div class="why-cell why-cell-diff why-cell-diff-<?php echo (int) $why_i; ?>">
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
                <a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( $why_cta_label ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php // The Our Story section moved to the About page (page-templates/page-about-us.php). ?>
<div class="text-marquee" aria-hidden="true">
    <div class="text-marquee-track">
        <span class="text-marquee-row">Strategy<em>&bull;</em>Design<em>&bull;</em>Development<em>&bull;</em>Content<em>&bull;</em>SEO<em>&bull;</em>Paid media<em>&bull;</em></span>
        <span class="text-marquee-row">Strategy<em>&bull;</em>Design<em>&bull;</em>Development<em>&bull;</em>Content<em>&bull;</em>SEO<em>&bull;</em>Paid media<em>&bull;</em></span>
    </div>
</div>

<section class="process" id="process">
    <div class="container px-4">
        <div class="content">
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
            <div class="col-lg-6">
                <div class="content">
                    <h2 class="split-text-contact"><?php echo wp_kses_post( $contact_heading ); ?></h2>
                    <p class="sub-heading"><?php echo esc_html( $contact_subheading ); ?></p>
                </div>
            </div>
            <div class="col-lg-6 form">
                <div class="form-container">
                    <?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$latest_insights = new WP_Query( [
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
] );

if ( $latest_insights->have_posts() ) :
    $latest_insights_blog = get_permalink( (int) get_option( 'page_for_posts' ) );
    if ( ! $latest_insights_blog ) {
        $latest_insights_blog = home_url( '/blog/' );
    }
    ?>
    <section class="latest-insights" id="latest-insights">
        <div class="container px-4">
            <div class="latest-insights-head">
                <div class="content">
                    <h2><?php echo wp_kses_post( $latest_heading ); ?></h2>
                    <p class="sub-heading"><?php echo esc_html( $latest_subheading ); ?></p>
                </div>
                <a class="latest-insights-all" href="<?php echo esc_url( $latest_insights_blog ); ?>"><?php echo esc_html( $latest_cta_label ); ?></a>
            </div>

            <div class="row g-4">
                <?php while ( $latest_insights->have_posts() ) : $latest_insights->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'card' ); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php
    wp_reset_postdata();
endif;

get_footer();
?>
