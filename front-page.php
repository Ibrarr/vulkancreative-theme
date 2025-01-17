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
                <video autoplay muted loop playsinline>
                    <source src="<?php echo VC_TEMPLATE_URI . '/assets/images/webm/in-person.webm'; ?>" type="video/webm">
                    Your browser does not support the video tag.
                </video>
                <h3>In-Person Approach</h3>
                <p>We work closely with you, offering a personal touch that builds trust and drives success.</p>
            </div>
            <div class="col-lg-4 why-boxes">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo VC_TEMPLATE_URI . '/assets/images/webm/tailored-solutions.webm'; ?>" type="video/webm">
                    Your browser does not support the video tag.
                </video>
                <h3>Tailored Solutions</h3>
                <p>Every strategy is customized to fit your unique brand and goals-no one-size-fits-all here.</p>
            </div>
            <div class="col-lg-4 why-boxes">
                <video autoplay muted loop playsinline>
                    <source src="<?php echo VC_TEMPLATE_URI . '/assets/images/webm/proven-results.webm'; ?>" type="video/webm">
                    Your browser does not support the video tag.
                </video>
                <h3>Proven Results</h3>
                <p>Our track record speaks for itself, delivering impactful outcomes that grow your business.</p>
            </div>
        </div>
        <a href="#contact" class="button">Learn more</a>
    </div>
</section>

<section class="story">
    <div class="container px-4">

    </div>
</section>

<?php
get_footer();
?>