<?php
get_header();

// Heading highlight defaults: map a plain-text default to a span-highlighted version.
// If the ACF value is empty or matches the plain default, use the highlighted version;
// otherwise the editor's own text is used as-is (they may include their own <span>).
$hp_heading_highlights = [
	'hp_results_heading'      => [ 'Results That Matter',              'Results That <span>Matter</span>' ],
	'hp_process_heading'      => [ 'A Clear Path From Spark to Scale', 'A Clear Path From <span>Spark to Scale</span>' ],
	'hp_testimonials_heading' => [ 'Trusted by Ambitious Brands',      'Trusted by <span>Ambitious Brands</span>' ],
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
$hero_subheading = get_field('hp_hero_subheading') ?: 'We forge brands and websites that perform. Strategy, design, development and content that turn attention into customers and lasting growth.';
$hero_button     = get_field('hp_hero_button_text') ?: 'Start a project';

// Logo bar
$logos_label = get_field('hp_logos_label') ?: 'Trusted by ambitious brands';

// Results
$results_tag     = get_field('hp_results_tag') ?: 'By the numbers';
$results_heading = hp_heading('hp_results_heading', $hp_heading_highlights);

// Process
$process_tag         = get_field('hp_process_tag') ?: 'How we work';
$process_heading     = hp_heading('hp_process_heading', $hp_heading_highlights);
$process_description  = get_field('hp_process_description') ?: 'A clear, collaborative process that takes you from first conversation to measurable results, with one partner accountable the whole way.';

// Testimonials
$testimonials_tag     = get_field('hp_testimonials_tag') ?: 'What clients say';
$testimonials_heading = hp_heading('hp_testimonials_heading', $hp_heading_highlights);
?>

<section class="hero" id="top">
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-8 content">
                <h1>
                    Turning Creative <span class="red spark">Sparks <?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/spark.svg' ) ?></span> Into Powerful
                    <span class="dynamic-text">
                        <span class="word">Brands<span class="red">.</span></span>
                        <span class="word">Websites<span class="red">.</span></span>
                        <span class="word">Marketing<span class="red">.</span></span>
                        <span class="word">Content<span class="red">.</span></span>
                    </span>
                </h1>
                <p class="split-text-hero"><?php echo esc_html( $hero_subheading ); ?></p>
                <div class="bottom"><a href="#contact" class="button"><?php echo esc_html( $hero_button ); ?></a></div>
            </div>
            <div class="col-lg-4 graphic" aria-hidden="true">
            </div>
        </div>
    </div>
</section>

<section class="logo-bar">
    <div class="container px-4">
        <p class="logo-bar-label"><?php echo esc_html( $logos_label ); ?></p>
    </div>
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

<section class="why" id="why">
    <div class="container px-4">
        <div class="top">
            <p class="tag">Why Vulkan</p>
            <h2>Why Choose <span>Vulkan</span>?</h2>
            <p class="sub-heading">Vulkan Creative is your dedicated partner, combining expertise with innovation to deliver results that last.</p>
        </div>
        <div class="why-box-container">
            <div class="why-boxes">
                <div class="image-container">
                    <img
                        loading="lazy"
                        src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person-reveal.webp'; ?>"
                        srcset="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person-reveal.webp'; ?> 100w,
                            <?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person-reveal.webp'; ?> 60w"
                        sizes="(max-width: 991px) 60px, 100px"
                        alt="In-Person Approach reveal"
                        class="reveal"
                    >
                    <img
                        loading="lazy"
                        src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person.webp'; ?>"
                        srcset="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person.webp'; ?> 100w,
                                <?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person.webp'; ?> 60w"
                        sizes="(max-width: 991px) 60px, 100px"
                        alt="In-Person Approach"
                        class="infinite"
                    >
                </div>
                <h3>In-Person Approach</h3>
                <p>We work closely with you, offering a personal touch that builds trust and drives success.</p>
            </div>
            <div class="why-boxes">
                <div class="image-container">
                    <img
                        loading="lazy"
                        src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions-reveal.webp'; ?>"
                        srcset="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions-reveal.webp'; ?> 100w,
                            <?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions-reveal.webp'; ?> 60w"
                        sizes="(max-width: 991px) 60px, 100px"
                        alt="Tailored Solutions reveal"
                        class="reveal"
                    >
                    <img
                        loading="lazy"
                        src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions.webp'; ?>"
                        srcset="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions.webp'; ?> 100w,
                            <?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions.webp'; ?> 60w"
                        sizes="(max-width: 991px) 60px, 100px"
                        alt="Tailored Solutions"
                        class="infinite"
                    >
                </div>
                <h3>Tailored Solutions</h3>
                <p>Every strategy is customised to fit your unique brand and goals, with no one-size-fits-all here.</p>
            </div>
            <div class="why-boxes">
                <div class="image-container">
                    <img
                        loading="lazy"
                        src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results-reveal.webp'; ?>"
                        srcset="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results-reveal.webp'; ?> 100w,
                            <?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results-reveal.webp'; ?> 60w"
                        sizes="(max-width: 991px) 60px, 100px"
                        alt="Proven Results reveal"
                        class="reveal"
                    >
                    <img
                        loading="lazy"
                        src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results.webp'; ?>"
                        srcset="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results.webp'; ?> 100w,
                            <?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results.webp'; ?> 60w"
                        sizes="(max-width: 991px) 60px, 100px"
                        alt="Proven Results"
                        class="infinite"
                    >
                </div>
                <h3>Proven Results</h3>
                <p>Our track record speaks for itself, delivering impactful outcomes that grow your business.</p>
            </div>
        </div>
    </div>
</section>

<section class="services" id="services">
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag">Our services</p>
                    <h2 class="split-text-services">Strategic <span>Solutions</span> Tailored to Your <span>Vision</span>.</h2>
                    <p class="sub-heading">Discover a full suite of marketing and web solutions at Vulkan Creative, where strategy meets creativity to elevate your brand’s impact and reach.</p>
                    <a href="#contact" class="button">Start a project</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="service-bento">
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

                    foreach ($services as $service) {
                        $title = $service->name;
                        $description = term_description($service->term_id, 'service');
                        $icon = get_field('icon', 'service_' . $service->term_id);
                        $icon_url = trailingslashit( home_url('/wp-content/themes/vulkancreative-theme/assets/images/icons/services') ) . ltrim($icon, '/');
                        ?>
                        <div class="service">
                            <span class="service-icon">
                                <img
                                        loading="lazy"
                                        src="<?php echo $icon_url; ?>"
                                        sizes="(max-width: 991px) 36px, 44px"
                                        alt="<?php echo $title; ?>"
                                />
                            </span>
                            <div class="content-service">
                                <h3><?php echo $title; ?></h3>
                                <?php echo $description; ?>
                            </div>
                            <a href="#contact" class="button" aria-label="Enquire about <?php echo esc_attr( $title ); ?>">Learn more</a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="process" id="process">
    <div class="container px-4">
        <div class="content">
            <p class="tag"><?php echo esc_html( $process_tag ); ?></p>
            <h2><?php echo wp_kses_post( $process_heading ); ?></h2>
            <p class="sub-heading"><?php echo esc_html( $process_description ); ?></p>
        </div>
        <div class="process-steps">
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
        <div class="content">
            <p class="tag"><?php echo esc_html( $testimonials_tag ); ?></p>
            <h2><?php echo wp_kses_post( $testimonials_heading ); ?></h2>
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
        <div class="splide" id="testimonial-splide" aria-label="Client testimonials">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php if ( have_rows('hp_testimonials_items') ) : ?>
                        <?php while ( have_rows('hp_testimonials_items') ) : the_row(); ?>
                            <li class="splide__slide">
                                <div class="testimonial-card">
                                    <blockquote>
                                        <p>&ldquo;<?php echo esc_html( get_sub_field('quote') ); ?>&rdquo;</p>
                                        <cite>
                                            <span class="t-name"><?php echo esc_html( get_sub_field('name') ); ?></span>
                                            <span class="t-company"><?php echo esc_html( get_sub_field('company') ); ?></span>
                                        </cite>
                                    </blockquote>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <li class="splide__slide">
                            <div class="testimonial-card">
                                <blockquote>
                                    <p>&ldquo;Working with Vulkan Creative transformed how we approach our marketing. The results speak for themselves.&rdquo;</p>
                                    <cite>
                                        <span class="t-name">Client Name</span>
                                        <span class="t-company">Company Name</span>
                                    </cite>
                                </blockquote>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="testimonial-card">
                                <blockquote>
                                    <p>&ldquo;Professional, responsive and genuinely invested in our success. They feel like an extension of our team.&rdquo;</p>
                                    <cite>
                                        <span class="t-name">Client Name</span>
                                        <span class="t-company">Company Name</span>
                                    </cite>
                                </blockquote>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="testimonial-card">
                                <blockquote>
                                    <p>&ldquo;They took the time to understand our market and built us a brand and website we are genuinely proud of.&rdquo;</p>
                                    <cite>
                                        <span class="t-name">Client Name</span>
                                        <span class="t-company">Company Name</span>
                                    </cite>
                                </blockquote>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="story" id="story">
    <div class="container px-4">
        <div class="content">
            <h2>Our <span>Story</span></h2>
            <p class="split-text-story">At Vulkan Creative, we believe in the power of storytelling to connect with audiences. Our journey is rooted in a passion for innovation and a commitment to helping businesses thrive in the digital landscape.</p>
            <div class="bottom"><a href="#watch" class="button">Watch</a></div>
        </div>
        <div class="video-wrapper" id="watch">
            <video
                    id="our-story"
                    class="video-js vjs-theme-city"
                    controls
                    preload="auto"
                    data-setup='{}'
                    title="Our story"
            >
                <source src="https://vulkancreative.com/wp-content/VulkanTrailer.mp4" type="video/mp4" />
            </video>
        </div>
    </div>
</section>

<section class="contact" id="contact">
    <div class="container px-4">
        <div class="row gx-5">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag">Connect</p>
                    <h2 class="split-text-contact">Have a <span>Project</span> You Want To Discuss?</h2>
                    <p class="sub-heading">Let’s build something powerful together. Share your vision and we’ll reply within one working day.</p>
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
get_footer();
?>
