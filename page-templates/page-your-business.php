<?php
/*
Template Name: Your Business
*/
get_header();

// Heading highlight defaults: map plain-text defaults to span-highlighted versions.
// If the ACF value matches the plain-text default (or is empty), use the highlighted version.
// If the user has customised the heading, their text is used as-is (they can add <span> tags).
$heading_highlights = [
    'yb_hero_heading'     => [ 'Ready to Forge Ahead?',             'Ready to <span>Forge Ahead</span>?' ],
    'yb_problem_heading'  => [ 'Sound Familiar?',                   'Sound <span>Familiar</span>?' ],
    'yb_solution_heading' => [ 'Two Pillars. One Partner.',          'Two Pillars. <span>One Partner.</span>' ],
    'yb_outcomes_heading' => [ 'The Results That Matter',            'The Results That <span>Matter</span>' ],
    'yb_trust_heading'    => [ 'Recognised Partners',               'Recognised <span>Partners</span>' ],
    'yb_cta_heading'      => [ "Let's Build Something That Works",  "Let's Build Something That <span>Works</span>" ],
];
function yb_heading( $field_name, $highlights ) {
    $val = get_field( $field_name );
    if ( isset( $highlights[ $field_name ] ) ) {
        if ( ! $val || $val === $highlights[ $field_name ][0] ) {
            return $highlights[ $field_name ][1];
        }
    }
    return $val ?: '';
}

// Hero
$hero_heading    = yb_heading( 'yb_hero_heading', $heading_highlights );
$hero_subheading = get_field('yb_hero_subheading') ?: 'You read the article. You know the problem. Now let\'s build the solution. Vulkan Creative helps SMEs turn scattered marketing into a joined-up system that actually delivers.';

// Problem
$problem_tag         = get_field('yb_problem_tag') ?: 'The problem';
$problem_heading     = yb_heading( 'yb_problem_heading', $heading_highlights );
$problem_description = get_field('yb_problem_description') ?: 'Most SME marketing fails because it focuses on the business and not the buyer. You end up with a patchwork of disconnected activity that looks busy but doesn\'t convert.';

// Solution
$solution_tag         = get_field('yb_solution_tag') ?: 'How we solve it';
$solution_heading     = yb_heading( 'yb_solution_heading', $heading_highlights );
$solution_description = get_field('yb_solution_description') ?: 'We focus on what your customers are trying to achieve, what\'s stopping them, and what they need to believe to choose you. Then we build the system that makes that decision easy.';
$pillar_1_heading     = get_field('yb_solution_pillar_1_heading') ?: 'A website that earns trust and drives action';
$pillar_1_description = get_field('yb_solution_pillar_1_description') ?: 'You can\'t market a weak website. Every campaign pushes people back to your site, so the foundations have to be strong. We design and develop bespoke websites that load quickly, work brilliantly on mobile, and guide visitors towards a clear next step.';
$pillar_2_heading     = get_field('yb_solution_pillar_2_heading') ?: 'Campaigns that work together, not in isolation';
$pillar_2_description = get_field('yb_solution_pillar_2_description') ?: 'We offer end-to-end campaign management, designed to compound over time rather than chase short-term spikes. Social media, paid advertising, content creation, email marketing, automation and brand building, all working towards your goal.';

// Outcomes
$outcomes_tag     = get_field('yb_outcomes_tag') ?: 'What you get';
$outcomes_heading = yb_heading( 'yb_outcomes_heading', $heading_highlights );

// Trust
$trust_tag     = get_field('yb_trust_tag') ?: 'Trusted by';
$trust_heading = yb_heading( 'yb_trust_heading', $heading_highlights );

// CTA
$cta_tag         = get_field('yb_cta_tag') ?: 'Get in touch';
$cta_heading     = yb_heading( 'yb_cta_heading', $heading_highlights );
$cta_subheading  = get_field('yb_cta_subheading') ?: 'No pitch decks, no fluff. Just a straight conversation about where you are, where you want to be, and how we can get you there.';
?>

<div class="yb-hero-wrapper">
<section class="yb-hero" id="top">
    <div class="page-hero-glow" aria-hidden="true"></div>
    <div class="container px-4">
        <div class="row gx">
            <div class="col-lg-6 content">
                <h1><?php echo wp_kses_post($hero_heading); ?></h1>
                <p class="split-text-yb-hero"><?php echo esc_html($hero_subheading); ?></p>
            </div>
            <div class="offset-xl-1 col-lg-5 hero-form">
                <div class="hero-form-container">
                    <?php vc_render_form( 2 ); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ( have_rows('yb_logo_bar_logos') ) : ?>
<section class="yb-logo-bar">
    <div class="splide" id="yb-logo-splide" aria-label="Companies we've worked with">
        <div class="splide__track">
            <ul class="splide__list">
                <?php while ( have_rows('yb_logo_bar_logos') ) : the_row();
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
<?php endif; ?>
</div>

<section class="yb-problem" id="yb-problem">
    <div class="container px-4">
        <div class="content">
            <h2 class="split-text-yb-problem"><?php echo wp_kses_post($problem_heading); ?></h2>
            <p class="sub-heading"><?php echo esc_html($problem_description); ?></p>
        </div>
        <div class="problem-grid">
            <?php if (have_rows('yb_problem_points')) : ?>
                <?php
                $problem_index = 0;
                $col_pattern = ['col-lg-7', 'col-lg-5', 'col-lg-5', 'col-lg-7'];
                ?>
                <div class="row gx-4 gy-4">
                    <?php while (have_rows('yb_problem_points')) : the_row(); ?>
                        <div class="<?php echo $col_pattern[$problem_index % 4]; ?>">
                            <div class="problem-card">
                                <h3><?php echo esc_html(get_sub_field('title')); ?></h3>
                                <p><?php echo esc_html(get_sub_field('description')); ?></p>
                            </div>
                        </div>
                        <?php $problem_index++; ?>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="row gx-4 gy-4">
                    <div class="col-lg-7">
                        <div class="problem-card">
                            <h3>Inconsistent results</h3>
                            <p>The website looks decent, yet leads are unpredictable. Traffic comes in but nothing converts.</p>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="problem-card">
                            <h3>Disconnected channels</h3>
                            <p>Social, paid, email and your website all running separately with no joined-up strategy behind them.</p>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="problem-card">
                            <h3>Wasted budget</h3>
                            <p>Paid advertising brings traffic but not the right conversations. You know money is going out but the ROI is unclear.</p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="problem-card">
                            <h3>No clear direction</h3>
                            <p>You want more enquiries, more sales, and a clearer brand, but every agency just offers the same templated approach.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="yb-solution" id="yb-solution">
    <div class="container px-4">
        <div class="content">
            <h2 class="split-text-yb-solution"><?php echo wp_kses_post($solution_heading); ?></h2>
            <p class="sub-heading"><?php echo esc_html($solution_description); ?></p>
        </div>
        <div class="pillar-editorial">
            <div class="pillar-block pillar-left">
                <div class="pillar-content">
                    <h3><?php echo esc_html($pillar_1_heading); ?></h3>
                    <p><?php echo esc_html($pillar_1_description); ?></p>
                </div>
            </div>
            <div class="pillar-block pillar-right">
                <div class="pillar-content">
                    <h3><?php echo esc_html($pillar_2_heading); ?></h3>
                    <p><?php echo esc_html($pillar_2_description); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="yb-outcomes" id="yb-outcomes">
    <div class="container px-4">
        <div class="content">
            <h2 class="split-text-yb-outcomes"><?php echo wp_kses_post($outcomes_heading); ?></h2>
        </div>
        <div class="outcomes-list">
            <?php if (have_rows('yb_outcomes_items')) : ?>
                <?php $outcome_index = 1; ?>
                <?php while (have_rows('yb_outcomes_items')) : the_row(); ?>
                    <div class="outcome-item">
                        <div class="outcome-content">
                            <h3><?php echo esc_html(get_sub_field('title')); ?></h3>
                            <p><?php echo esc_html(get_sub_field('description')); ?></p>
                        </div>
                    </div>
                    <?php $outcome_index++; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="outcome-item">
                    <div class="outcome-content">
                        <h3>More qualified leads</h3>
                        <p>Attract the right people with messaging that speaks to their needs, not just your features.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <div class="outcome-content">
                        <h3>Clear ROI reporting</h3>
                        <p>Know exactly what is working, what is not, and where your budget is best spent.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <div class="outcome-content">
                        <h3>One partner end-to-end</h3>
                        <p>No contractors, no outsourcing. You speak to the people actually doing the work.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <div class="outcome-content">
                        <h3>Messaging that sells</h3>
                        <p>Copy and creative built around what your customers need to hear, not what you want to say.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <div class="outcome-content">
                        <h3>A website that converts</h3>
                        <p>Strong UX, clean structure, and tracking that tells you what is working.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="yb-trust" id="yb-trust">
    <div class="container px-4">
        <div class="content">
            <h2 class="split-text-yb-trust"><?php echo wp_kses_post($trust_heading); ?></h2>
        </div>
        <div class="trust-logos">
            <?php if ( have_rows('yb_trust_partner_logos') ) : ?>
                <?php while ( have_rows('yb_trust_partner_logos') ) : the_row();
                    $logo = get_sub_field('logo');
                    $alt = get_sub_field('alt_text');
                    if ( $logo ) : ?>
                        <img loading="lazy" src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $alt ?: $logo['alt'] ?: $logo['title'] ); ?>" class="trust-logo">
                    <?php endif;
                endwhile; ?>
            <?php else : ?>
                <img loading="lazy" src="<?php echo VC_TEMPLATE_URI . '/assets/images/logos/google-partner.svg'; ?>" alt="Google Partner" class="trust-logo">
                <img loading="lazy" src="<?php echo VC_TEMPLATE_URI . '/assets/images/logos/hotjar-partner.svg'; ?>" alt="Hotjar Partner" class="trust-logo">
            <?php endif; ?>
        </div>
        <div class="splide" id="yb-testimonial-splide" aria-label="Client testimonials">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php if ( have_rows('yb_trust_testimonials') ) : ?>
                        <?php while ( have_rows('yb_trust_testimonials') ) : the_row(); ?>
                            <li class="splide__slide">
                                <div class="testimonial-card">
                                    <blockquote>
                                        <p>"<?php echo esc_html( get_sub_field('quote') ); ?>"</p>
                                        <cite><?php echo esc_html( get_sub_field('name') ); ?>, <?php echo esc_html( get_sub_field('company') ); ?></cite>
                                    </blockquote>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <li class="splide__slide">
                            <div class="testimonial-card">
                                <blockquote>
                                    <p>"Working with Vulkan Creative transformed how we approach our marketing. The results speak for themselves."</p>
                                    <cite>Client Name, Company Name</cite>
                                </blockquote>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="testimonial-card">
                                <blockquote>
                                    <p>"Professional, responsive, and genuinely invested in our success. They feel like an extension of our team."</p>
                                    <cite>Client Name, Company Name</cite>
                                </blockquote>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="yb-cta" id="yb-contact">
    <div class="container px-4">
        <div class="row gx-5">
            <div class="col-lg-6">
                <div class="content">
                    <h2 class="split-text-yb-cta"><?php echo wp_kses_post($cta_heading); ?></h2>
                    <p class="sub-heading"><?php echo esc_html($cta_subheading); ?></p>
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
get_footer();
?>
