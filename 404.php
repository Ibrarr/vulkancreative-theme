<?php
get_header();
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const baseUrl = window.location.origin;

        const anchorLinks = document.querySelectorAll('a[href^="#"]');

        anchorLinks.forEach(function(link) {
            const currentHref = link.getAttribute('href');
            link.setAttribute('href', baseUrl + '/' + currentHref);
        });
    });
</script>

<section class="error-section">
    <div class="container px-4">
       <h1>404</h1>
       <h2>Page not found</h2>
       <p>We apologise for the inconvenience. Please use the navigation menu to find the information you need, or click the button below.</p>
<!--       <a href="javascript:history.back()" class="button">Go back</a>-->
       <a href="/" class="button">Back to homepage</a>
    </div>
</section>

<?php
get_footer();
?>