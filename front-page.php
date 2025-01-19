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
                <a href="#contact" class="button">Forge ahead</a>
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
        <div class="bottom"><a href="#contact" class="button">Learn more</a></div>
    </div>
</section>

<section class="story">
    <div class="container px-4">
        <div class="content">
            <h2>Our <span>Story</span></h2>
            <p>At Vulkan Creative, we believe in the power of storytelling to connect with audiences. Our journey is rooted in a passion for innovation and a commitment to helping businesses thrive in the digital landscape.</p>
            <a href="#watch" class="button">Watch</a>
        </div>
        <div class="video-wrapper" id="watch">
            <iframe
                    src="https://www.youtube.com/embed/C0DPdy98e4c?si=Y5z5MHxS5uvEmwYC"
                    frameborder="0"
                    allow="encrypted-media"
                    title="Our story"
                    allowfullscreen
            ></iframe>
        </div>
    </div>
</section>

<section class="services">
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-6 content">
                <p class="tag">Our services</p>
                <h2>Strategic <span>Solutions</span> Tailored to Your <span>Vision</span>.</h2>
                <p>Discover a full suite of marketing and web solutions at Vulkan Creative, where strategy meets creativity to elevate your brand’s impact and reach.</p>
            </div>
            <div class="col-lg-6 services">
                services
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>