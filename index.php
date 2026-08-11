<?php
// Last-resort fallback: nothing should route here, but if a query ever does,
// render the shell and the not-found body rather than a blank page.
get_header();
get_template_part( 'template-parts/content', '404' );
get_footer();
