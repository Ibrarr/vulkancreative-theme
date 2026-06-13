<?php
/* Template Name: 404 Preview */

// Renders the 404 design at a stable URL for review and testing, since real
// 404s are canonical-redirected to the homepage on this site.

get_header();
get_template_part( 'template-parts/content', '404' );
get_footer();
?>
