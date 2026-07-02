<?php
/**
 * The 404 / "page not found" section.
 *
 * Shared by 404.php (the real error response) and the "404 Preview" page
 * template, so the design can be viewed at a stable URL — unknown URLs are
 * canonical-redirected to the homepage on this site, so the real 404 cannot
 * be reached directly for review.
 */
?>

<section class="error-section">
    <div class="error-glow" aria-hidden="true"></div>
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-8 content">
                <p class="error-code" aria-hidden="true">4<span>0</span>4</p>
                <h1>Page <span>not found</span>.</h1>
                <p class="sub-heading">The link may be broken or the page may have moved. Head back to the homepage, or tell us what you were looking for and we will point you the right way.</p>
                <div class="bottom">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">Back to the Homepage</a>
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button-ghost">Start a Project</a>
                </div>
            </div>
        </div>
    </div>
</section>
