<?php
get_header();

if (is_category()) {
    get_template_part( 'template-parts/archive', 'category' );
}

get_footer();