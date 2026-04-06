<?php
/*
Template Name: Your Business
*/
get_header();

// Hero
$hero_heading = get_field('yb_hero_heading') ?: 'Ready to Forge Ahead?';
$hero_subheading = get_field('yb_hero_subheading') ?: 'You read the article. You know the problem. Now let\'s build the solution. Vulkan Creative helps SMEs turn scattered marketing into a joined-up system that actually delivers.';
$hero_button_text = get_field('yb_hero_button_text') ?: 'Start the conversation';

// Problem
$problem_tag = get_field('yb_problem_tag') ?: 'The problem';
$problem_heading = get_field('yb_problem_heading') ?: 'Sound Familiar?';
$problem_description = get_field('yb_problem_description') ?: 'Most SME marketing fails because it focuses on the business and not the buyer. You end up with a patchwork of disconnected activity that looks busy but doesn\'t convert.';

// Solution
$solution_tag = get_field('yb_solution_tag') ?: 'How we solve it';
$solution_heading = get_field('yb_solution_heading') ?: 'Two Pillars. One Partner.';
$solution_description = get_field('yb_solution_description') ?: 'We focus on what your customers are trying to achieve, what\'s stopping them, and what they need to believe to choose you. Then we build the system that makes that decision easy.';
$pillar_1_heading = get_field('yb_solution_pillar_1_heading') ?: 'A website that earns trust and drives action';
$pillar_1_description = get_field('yb_solution_pillar_1_description') ?: 'You can\'t market a weak website. Every campaign pushes people back to your site, so the foundations have to be strong. We design and develop bespoke websites that load quickly, work brilliantly on mobile, and guide visitors towards a clear next step.';
$pillar_2_heading = get_field('yb_solution_pillar_2_heading') ?: 'Campaigns that work together, not in isolation';
$pillar_2_description = get_field('yb_solution_pillar_2_description') ?: 'We offer end-to-end campaign management, designed to compound over time rather than chase short-term spikes. Social media, paid advertising, content creation, email marketing, automation and brand building, all working towards your goal.';

// Outcomes
$outcomes_tag = get_field('yb_outcomes_tag') ?: 'What you get';
$outcomes_heading = get_field('yb_outcomes_heading') ?: 'The Results That Matter';

// Trust
$trust_tag = get_field('yb_trust_tag') ?: 'Trusted by';
$trust_heading = get_field('yb_trust_heading') ?: 'Recognised Partners';
$trust_quote = get_field('yb_trust_testimonial_quote') ?: 'Working with Vulkan Creative transformed how we approach our marketing. The results speak for themselves.';
$trust_name = get_field('yb_trust_testimonial_name') ?: 'Client Name';
$trust_company = get_field('yb_trust_testimonial_company') ?: 'Company Name';

// CTA
$cta_tag = get_field('yb_cta_tag') ?: 'Get in touch';
$cta_heading = get_field('yb_cta_heading') ?: 'Let\'s Build Something That Works';
$cta_subheading = get_field('yb_cta_subheading') ?: 'No pitch decks, no fluff. Just a straight conversation about where you are, where you want to be, and how we can get you there.';
?>

<section class="yb-hero" id="top">
    <div class="container px-4">
        <div class="row gx-5">
            <div class="col-lg-7 content">
                <p class="tag">From Your Business Magazine</p>
                <h1><?php echo esc_html($hero_heading); ?></h1>
                <p class="split-text-yb-hero"><?php echo esc_html($hero_subheading); ?></p>
                <div class="bottom"><a href="#yb-hero-form" class="button disable-custom-cursor"><?php echo esc_html($hero_button_text); ?></a></div>
            </div>
            <div class="col-lg-5 hero-form" id="yb-hero-form">
                <div class="form-container">
                    <?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
                </div>
                <div class="hero-trust-logos">
                    <img loading="lazy" src="<?php echo VC_TEMPLATE_URI . '/assets/images/logos/google-partner.svg'; ?>" alt="Google Partner" class="trust-logo">
                    <img loading="lazy" src="<?php echo VC_TEMPLATE_URI . '/assets/images/logos/hotjar-partner.svg'; ?>" alt="Hotjar Partner" class="trust-logo">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="yb-problem" id="yb-problem">
    <div class="container px-4">
        <div class="content">
            <p class="tag"><?php echo esc_html($problem_tag); ?></p>
            <h2 class="split-text-yb-problem"><?php echo esc_html($problem_heading); ?></h2>
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
            <p class="tag"><?php echo esc_html($solution_tag); ?></p>
            <h2 class="split-text-yb-solution"><?php echo esc_html($solution_heading); ?></h2>
            <p class="sub-heading"><?php echo esc_html($solution_description); ?></p>
        </div>
        <div class="pillar-editorial">
            <div class="pillar-block pillar-left">
                <span class="pillar-number">01</span>
                <div class="pillar-content">
                    <h3><?php echo esc_html($pillar_1_heading); ?></h3>
                    <p><?php echo esc_html($pillar_1_description); ?></p>
                </div>
            </div>
            <div class="pillar-block pillar-right">
                <span class="pillar-number">02</span>
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
            <p class="tag"><?php echo esc_html($outcomes_tag); ?></p>
            <h2 class="split-text-yb-outcomes"><?php echo esc_html($outcomes_heading); ?></h2>
        </div>
        <div class="outcomes-list">
            <?php if (have_rows('yb_outcomes_items')) : ?>
                <?php $outcome_index = 1; ?>
                <?php while (have_rows('yb_outcomes_items')) : the_row(); ?>
                    <div class="outcome-item">
                        <span class="outcome-number"><?php echo str_pad($outcome_index, 2, '0', STR_PAD_LEFT); ?></span>
                        <div class="outcome-content">
                            <h3><?php echo esc_html(get_sub_field('title')); ?></h3>
                            <p><?php echo esc_html(get_sub_field('description')); ?></p>
                        </div>
                    </div>
                    <?php $outcome_index++; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="outcome-item">
                    <span class="outcome-number">01</span>
                    <div class="outcome-content">
                        <h3>More qualified leads</h3>
                        <p>Attract the right people with messaging that speaks to their needs, not just your features.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <span class="outcome-number">02</span>
                    <div class="outcome-content">
                        <h3>Clear ROI reporting</h3>
                        <p>Know exactly what is working, what is not, and where your budget is best spent.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <span class="outcome-number">03</span>
                    <div class="outcome-content">
                        <h3>One partner end-to-end</h3>
                        <p>No contractors, no outsourcing. You speak to the people actually doing the work.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <span class="outcome-number">04</span>
                    <div class="outcome-content">
                        <h3>Messaging that sells</h3>
                        <p>Copy and creative built around what your customers need to hear, not what you want to say.</p>
                    </div>
                </div>
                <div class="outcome-item">
                    <span class="outcome-number">05</span>
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
            <p class="tag"><?php echo esc_html($trust_tag); ?></p>
            <h2 class="split-text-yb-trust"><?php echo esc_html($trust_heading); ?></h2>
        </div>
        <div class="testimonial">
            <span class="decorative-quote">&ldquo;</span>
            <blockquote>
                <p class="split-text-yb-trust-quote">&ldquo;<?php echo esc_html($trust_quote); ?>&rdquo;</p>
                <cite>&mdash; <?php echo esc_html($trust_name); ?>, <?php echo esc_html($trust_company); ?></cite>
            </blockquote>
        </div>
        <div class="trust-logos">
            <img loading="lazy" src="<?php echo VC_TEMPLATE_URI . '/assets/images/logos/google-partner.svg'; ?>" alt="Google Partner" class="trust-logo">
            <img loading="lazy" src="<?php echo VC_TEMPLATE_URI . '/assets/images/logos/hotjar-partner.svg'; ?>" alt="Hotjar Partner" class="trust-logo">
        </div>
    </div>
</section>

<section class="yb-cta" id="yb-contact">
    <div class="container px-4">
        <div class="row gx-5">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag"><?php echo esc_html($cta_tag); ?></p>
                    <h2 class="split-text-yb-cta"><?php echo esc_html($cta_heading); ?></h2>
                    <p class="sub-heading"><?php echo esc_html($cta_subheading); ?></p>
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
