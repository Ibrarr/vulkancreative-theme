<?php
get_header();

// Section headings are composed from three editable parts (start, red, end)
// by vc_heading_parts() in inc/template-functions.php; the red part renders
// as the standard <span> highlight. The fallback strings below only apply
// when all three parts are blank.

// Hero
$hero_subheading       = get_field('hp_hero_subheading') ?: 'Vulkan Creative is a London digital agency run by its two founders. We plan, design, build and market in-house, so the people you meet are the people doing the work.';
$hero_button           = get_field('hp_hero_button_text') ?: 'Start a Project';
$hero_secondary_button = get_field('hp_hero_secondary_button_text') ?: 'See Our Work';

// Results
$results_heading = vc_heading_parts( 'hp_results_heading', false, 'The <span>numbers</span> so far' );

// Services
$services_heading     = vc_heading_parts( 'hp_services_heading', false, 'Six services, <span>one team</span>' );
$services_description = get_field('hp_services_description') ?: 'Take one service or combine them. The same two founders lead every piece, so nothing is handed between suppliers.';

// Work
$work_heading = vc_heading_parts( 'hp_work_heading', false, 'Forged with <span>our clients</span>.' );
$work_cases   = [];
$case_studies = new WP_Query([
    'post_type'      => 'case_study',
    'posts_per_page' => 3,
    'no_found_rows'  => true,
    'meta_key'       => 'cs_featured',
    'meta_value'     => '1',
]);
if ( $case_studies->have_posts() ) {
    while ( $case_studies->have_posts() ) { $case_studies->the_post();
        $work_cases[] = [
            'client'  => get_field('cs_client_name') ?: get_the_title(),
            'sector'  => get_field('cs_sector'),
            'summary' => get_field('cs_summary'),
            'value'   => get_field('cs_metric_value'),
            'label'   => get_field('cs_metric_label'),
            'image'   => get_field('cs_image'),
            // Cards link to the case studies' own pages (July 2026,
            // the case-studies build); the outro keeps the contact route.
            'link'    => get_permalink(),
        ];
    }
    wp_reset_postdata();
}

// Our Work
$our_work_heading    = vc_heading_parts( 'hp_our_work_heading', false, 'More of <span>our work</span>.' );
// "More of our work" only reads right under the featured case studies. When
// that section is absent this is the first work on the page, so the heading
// drops its "More of" and becomes "Our work."
if ( ! $work_cases ) {
    $our_work_heading = preg_replace_callback( '/^More of\s+(<span>)?(\w)/iu', function ( $m ) {
        return $m[1] . mb_strtoupper( $m[2] );
    }, $our_work_heading );
}
$our_work_subheading = get_field('hp_our_work_subheading') ?: 'Not every project gets a full case study. Here is a wider selection of the brands, websites and campaigns we have delivered.';
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
            // Tiles link to the project's own page (July 2026, the
            // work-pages build); the live-site link sits in that
            // page's hero instead.
            'link'        => get_permalink( $project_id ),
            'service'     => $service_label,
        ];
    }
}

// Why
$why_heading    = vc_heading_parts( 'hp_why_heading', false, 'Three things we <span>do not compromise</span> on' );
$why_subheading = get_field('hp_why_subheading') ?: 'You work directly with the two founders. These are the rules we hold to on every project.';

$why_items_default = [
	[
		'title'       => 'Hands on and in person',
		'description' => 'We sit down with you and learn how the business actually runs. You deal with the people doing the work, not an account queue.',
		'proof'       => 'Both founders on every call',
	],
	[
		'title'       => 'Built for you, never from a template',
		'description' => 'We build every brand, website and campaign around your audience, from the ground up. Nothing off the shelf, nothing recycled.',
		'proof'       => 'Every build from a blank canvas',
	],
	[
		'title'       => 'Results you can measure',
		'description' => 'We tie every engagement to numbers that matter: enquiries, rankings, revenue. You always know what is working and why.',
		'proof'       => 'Reported monthly in plain English',
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
$why_note_title = get_field('hp_why_note_title') ?: 'You always know who is responsible';
$why_note_text  = get_field('hp_why_note_text') ?: 'We are not the cheapest option. One partner is answerable for strategy, design, build and growth, and if something is not working you hear it from us first, with a plan to fix it.';
$why_cta_text   = get_field('hp_why_cta_text') ?: 'Want to talk it through?';
$why_cta_label  = get_field('hp_why_cta_label') ?: 'Start a Project';

// Process
$process_heading     = vc_heading_parts( 'hp_process_heading', false, 'How a <span>project</span> runs' );
$process_description = get_field('hp_process_description') ?: 'Four stages, from the first conversation to reporting on results. The same two people lead each stage.';

// Testimonials (the rating chip reads Global Settings via vc_google_reviews())
$testimonials_heading = vc_heading_parts( 'hp_testimonials_heading', false, 'What <span>clients</span> say' );

// Contact
$contact_heading    = vc_heading_parts( 'hp_contact_heading', false, 'Have a <span>project</span> you want to discuss?' );
// Non-breaking spaces keep "you want" and "to discuss" together once the
// SplitText reveal reverts (the nbsp only holds after the revert, not during
// the split). The fallback above already carries them, so this is a no-op there.
$contact_heading    = str_replace( array( 'you want', 'to discuss' ), array( 'you want', 'to discuss' ), $contact_heading );
$contact_subheading = get_field('hp_contact_subheading') ?: 'Tell us where you want to be. We will reply within one working day with a clear next step.';

// Latest insights
$latest_heading    = vc_heading_parts( 'hp_latest_heading', false, 'Latest <span>insights</span>.' );
$latest_subheading = get_field('hp_latest_subheading') ?: 'Articles on web, SEO, AI and marketing, written by the two of us from what we build and test.';
$latest_cta_label  = get_field('hp_latest_cta_label') ?: 'View All Insights';
?>

<section class="hero" id="top">
    <div class="hero-glow" aria-hidden="true"></div>
    <?php // Below lg the statue is a static poster and one of the first big paints,
    // so it is server-rendered and preloaded (header.php) rather than injected by
    // JS after the bundle parses. At lg+ the <img> carries a transparent pixel:
    // the three.js scene fades in over the glow, and statue-hero.js swaps the
    // desktop poster in only for reduced motion or a failed scene. ?>
    <div class="graphic" aria-hidden="true">
        <picture>
            <source media="(max-width: 991.98px)"
                    srcset="<?php echo esc_url( VC_TEMPLATE_URI . '/assets/images/hero/statue-mobile-800.webp' ); ?> 800w, <?php echo esc_url( VC_TEMPLATE_URI . '/assets/images/hero/statue-mobile.webp' ); ?> 1178w"
                    sizes="100vw">
            <img class="hero-poster" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" width="1178" height="1335" fetchpriority="high" decoding="async">
        </picture>
    </div>
    <div class="hero-content-wrap">
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-9 col-xl-8 content">
                    <h1>
                        <span class="h1-line">We forge</span>
                        <span class="dynamic-text" aria-hidden="true">
                            <span class="word">brands</span>
                            <span class="word">websites</span>
                            <span class="word">marketing</span>
                            <span class="word">content</span>
                        </span>
                        <?php // The rotating words are decorative; give assistive tech and search
                        // engines one clean, keyword-complete reading of the heading. ?>
                        <span class="visually-hidden">brands, websites, marketing and content</span>
                        <span class="h1-line">that bring in customers<span class="red">.</span></span>
                    </h1>
                    <p class="split-text-hero"><?php echo esc_html( $hero_subheading ); ?></p>
                    <div class="bottom">
                        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button"><?php echo esc_html( $hero_button ); ?></a>
                        <a href="<?php echo esc_url( $work_cases ? '#work' : ( $work_projects ? '#our-work' : home_url( '/work/' ) ) ); ?>" class="button-ghost"><?php echo esc_html( $hero_secondary_button ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-marquee">
        <div class="splide" id="logo-splide" aria-label="Companies we've worked with">
            <div class="splide__track">
                <div class="splide__list">
                    <?php $logo_i = 0;
                    while ( have_rows('worked_with_logos', 'options') ) : the_row();
                        echo vc_logo_slide( get_sub_field('logo'), $logo_i++ );
                    endwhile; ?>
                </div>
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
                            <span class="stat-number"><?php echo esc_html( vc_review_tokens( get_sub_field('value') ) ); ?></span>
                            <p class="stat-label"><?php echo esc_html( vc_review_tokens( get_sub_field('label') ) ); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else :
                $placeholder_stats = [
                    [ '120+', 'Projects delivered' ],
                    [ '5.0',  'Rating on Google' ],
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
            // Parent services in the order set in Global Settings > Service List
            // (parents ticked to show on the homepage rail).
            $services = vc_ordered_services( 'homepage' );

            $service_i = 1;
            foreach ($services as $service) {
                $title = $service->name;
                $description = wp_strip_all_tags( term_description($service->term_id, 'service') );
                $icon = get_field('icon', 'service_' . $service->term_id);
                $icon_url = VC_TEMPLATE_URI . '/assets/images/icons/services/' . ltrim($icon, '/');
                ?>
                <a class="service-row" href="<?php echo esc_url( get_term_link( $service ) ); ?>">
                    <img class="service-icon" loading="lazy" decoding="async" src="<?php echo esc_url( $icon_url ); ?>" alt="" width="130" height="130">
                    <div class="service-main">
                        <div class="service-title-row">
                            <h3 class="service-title"><?php echo esc_html( $title ); ?></h3>
                        </div>
                        <span class="service-desc"><?php echo esc_html( $description ); ?></span>
                    </div>
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

<?php // Hidden outright until a featured case study exists (Sep 2026). ?>
<?php if ( $work_cases ) : ?>
<section class="work" id="work">
    <div class="container px-4">
        <div class="content">
            <h2><?php echo wp_kses_post( $work_heading ); ?></h2>
        </div>
            <div class="work-showcase">
                <div class="case-list">
                    <?php foreach ( $work_cases as $work_i => $work_case ) : ?>
                        <a class="case-row<?php echo $work_i === 0 ? ' is-active' : ''; ?>" href="<?php echo esc_url( $work_case['link'] ); ?>" data-case="<?php echo (int) $work_i; ?>" aria-label="Read the <?php echo esc_attr( $work_case['client'] ); ?> case study">
                            <?php if ( $work_case['image'] ) : ?>
                                <span class="case-bg" aria-hidden="true">
                                    <?php echo vc_image( $work_case['image'], 'large', [ 'sizes' => '(min-width: 992px) 560px, 100vw' ] ); ?>
                                </span>
                            <?php endif; ?>
                            <div class="case-overlay">
                                <div class="case-text">
                                    <?php if ( $work_case['sector'] ) : ?><span class="case-sector"><?php echo esc_html( $work_case['sector'] ); ?></span><?php endif; ?>
                                    <h3 class="case-client"><?php echo esc_html( $work_case['client'] ); ?></h3>
                                    <span class="case-summary"><?php echo esc_html( $work_case['summary'] ); ?></span>
                                </div>
                                <span class="case-metric">
                                    <span class="metric-value"><?php echo esc_html( $work_case['value'] ); ?></span>
                                    <span class="metric-label"><?php echo esc_html( $work_case['label'] ); ?></span>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="case-stage" aria-hidden="true">
                    <?php foreach ( $work_cases as $work_i => $work_case ) : ?>
                        <?php if ( $work_case['image'] ) : ?>
                            <?php echo vc_image( $work_case['image'], 'header-image', [
                                'class'     => 'stage-img' . ( $work_i === 0 ? ' is-active' : '' ),
                                'data-case' => (int) $work_i,
                                'sizes'     => '(min-width: 992px) 50vw, 100vw',
                            ] ); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <p class="work-outro">Your project could be next. <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a></p>
    </div>
</section>
<?php endif; ?>

<?php // Hidden outright until the wheel has a project; is-first-work takes the
// white pairing when the case-study section above is absent (Sep 2026). ?>
<?php if ( $work_projects ) : ?>
<section class="our-work<?php echo $work_cases ? '' : ' is-first-work'; ?>" id="our-work">
    <div class="container px-4">
        <?php
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
<?php endif; ?>

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
        <?php get_template_part( 'template-parts/partner-logos', null, [ 'surface' => 'dark' ] ); ?>
    </div>
</section>

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
        <?php get_template_part( 'template-parts/offer-links' ); ?>
    </div>
</section>

<section class="testimonials" id="testimonials">
    <div class="container px-4">
        <div class="testimonials-head">
            <div class="content">
                <h2><?php echo wp_kses_post( $testimonials_heading ); ?></h2>
            </div>
            <?php get_template_part( 'template-parts/rating-chip' ); ?>
        </div>
        <?php if ( have_rows('hp_testimonials_logos') ) : ?>
            <div class="trust-logos">
                <?php while ( have_rows('hp_testimonials_logos') ) : the_row();
                    $logo = get_sub_field('logo');
                    $alt = get_sub_field('alt_text');
                    if ( $logo ) : ?>
                        <?php echo vc_image( $logo, 'medium', [ 'class' => 'trust-logo', 'alt' => $alt ?: vc_logo_alt( $logo ), 'sizes' => '160px' ] ); ?>
                    <?php endif;
                endwhile; ?>
            </div>
        <?php endif; ?>
        <?php
        $testimonial_items = vc_testimonial_items( 6 );
        if ( $testimonial_items ) : ?>
            <?php get_template_part( 'template-parts/testimonial-spotlight', null, [ 'items' => $testimonial_items ] ); ?>
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
                    <?php vc_render_form( 2 ); ?>
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
