<?php
get_header();

?>

<section class="hero">
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-7 content">
                <h1>
                    Turning Creative <span class="red spark">Sparks <?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/spark.svg' ) ?></span> Into Powerful
                    <span class="dynamic-text">
                        <span class="word">Brands<span class="red">.</span></span>
                        <span class="word">Websites<span class="red">.</span></span>
                        <span class="word">Marketing<span class="red">.</span></span>
                        <span class="word">Content<span class="red">.</span></span>
                    </span>
                </h1>
                <p>We forge strong partnerships that elevate businesses into industry leaders, fostering growth and long-term success.</p>
                <a href="#contact" class="button disable-custom-cursor">Forge ahead</a>
            </div>
            <div class="col-lg-5 graphic">
                <img src="/wp-content/uploads/2025/01/vulkantransparent.webp" alt="VC">
            </div>
        </div>
    </div>
</section>

<section class="why">
    <div class="container px-4">
        <div class="top">
            <h2>Why Choose <span>Vulkan</span>?</h2>
            <p>Vulkan Creative is your dedicated partner, combining expertise with innovation to deliver results that last.</p>
        </div>
        <div class="row why-box-container">
            <div class="col-lg-4 why-boxes">
                <div class="image-container">
                    <img src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person-reveal.webp'; ?>" class="reveal" alt="In-Person Approach reveal">
                    <img src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/in-person.webp'; ?>"  class="infinite" alt="In-Person Approach">
                </div>
                <h3>In-Person Approach</h3>
                <p>We work closely with you, offering a personal touch that builds trust and drives success.</p>
            </div>
            <div class="col-lg-4 why-boxes">
                <div class="image-container">
                    <img src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions-reveal.webp'; ?>"  class="reveal" alt="Tailored Solutions reveal">
                    <img src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/tailored-solutions.webp'; ?>" class="infinite" alt="Tailored Solutions">
                </div>
                <h3>Tailored Solutions</h3>
                <p>Every strategy is customized to fit your unique brand and goals-no one-size-fits-all here.</p>
            </div>
            <div class="col-lg-4 why-boxes">
                <div class="image-container">
                    <img src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results-reveal.webp'; ?>" class="reveal" alt="Proven Results reveal">
                    <img src="<?php echo VC_TEMPLATE_URI . '/assets/images/animated-icons/proven-results.webp'; ?>" class="infinite" alt="Proven Results">
                </div>
                <h3>Proven Results</h3>
                <p>Our track record speaks for itself, delivering impactful outcomes that grow your business.</p>
            </div>
        </div>
        <div class="bottom"><a href="#contact" class="button disable-custom-cursor">Learn more</a></div>
    </div>
</section>

<section class="story">
    <div class="container px-4">
        <div class="content">
            <h2>Our <span>Story</span></h2>
            <p>At Vulkan Creative, we believe in the power of storytelling to connect with audiences. Our journey is rooted in a passion for innovation and a commitment to helping businesses thrive in the digital landscape.</p>
            <a href="#watch" class="button disable-custom-cursor">Watch</a>
        </div>
        <div class="video-wrapper" id="watch">
            <video
                    id="our-story"
                    class="video-js vjs-theme-city"
                    controls
                    preload="auto"
                    poster="/wp-content/uploads/2025/01/vulkantransparent.webp"
                    data-setup='{
                        "techOrder": ["youtube"],
                        "sources": [{ "type": "video/youtube", "src": "https://www.youtube.com/watch?v=C0DPdy98e4c" }]
                    }'
                    title="Our story"
            >
            </video>
        </div>
    </div>
</section>

<section class="services">
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag">Our services</p>
                    <h2>Strategic <span>Solutions</span> Tailored to Your <span>Vision</span>.</h2>
                    <p>Discover a full suite of marketing and web solutions at Vulkan Creative, where strategy meets creativity to elevate your brand’s impact and reach.</p>
                </div>
            </div>
            <div class="col-lg-6 service-container">
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
                    ?>
                    <div class="service">
                        <img src="<?php echo $icon; ?>" alt="<?php echo $title; ?>">
                        <h3><?php echo $title; ?></h3>
                        <?php echo $description; ?>
                        <a href="#contact" class="button">Learn more</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<section class="contact">
    <div class="container px-4">

    </div>
</section>

<?php
get_footer();
?>