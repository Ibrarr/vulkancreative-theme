<?php
get_header();

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
                <p class="split-text-hero">We forge strong partnerships that elevate businesses into industry leaders, fostering growth and long-term success.</p>
                <div class="bottom"><a href="#contact" class="button disable-custom-cursor">Forge ahead</a></div>
            </div>
            <div class="col-lg-4 graphic">
                <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.98/build/spline-viewer.js"></script>
                <spline-viewer loading-anim-type="spinner-small-light" url="https://prod.spline.design/TGbj8tyAqN3q8iiZ/scene.splinecode"></spline-viewer>
            </div>
        </div>
    </div>
</section>

<section class="why" id="why">
    <div class="container px-4">
        <div class="top">
            <h2>Why Choose <span>Vulkan</span>?</h2>
            <p class="sub-heading">Vulkan Creative is your dedicated partner, combining expertise with innovation to deliver results that last.</p>
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
                <p>Every strategy is customised to fit your unique brand and goals-no one-size-fits-all here.</p>
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

<section class="story" id="story">
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
                    data-setup='{}'
                    title="Our story"
            >
                <source src="/wp-content/uploads/2025/04/VulkanTrailer.mp4" type="video/mp4" />
            </video>

        </div>
    </div>
</section>

<section class="services" id="services">
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag">Our services</p>
                    <h2>Strategic <span>Solutions</span> Tailored to Your <span>Vision</span>.</h2>
                    <p class="sub-heading">Discover a full suite of marketing and web solutions at Vulkan Creative, where strategy meets creativity to elevate your brand’s impact and reach.</p>
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

<section class="contact" id="contact">
    <div class="container px-4">
        <div class="row gx-5">
            <div class="col-lg-6">
                <div class="content">
                    <p class="tag">Connect</p>
                    <h2>Have a <span>Project</span> You Want To Discuss?</h2>
                    <p>Let’s build something powerful together – share your vision with us.</p>
                </div>
            </div>
            <div class="col-lg-6 form">
                <?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>