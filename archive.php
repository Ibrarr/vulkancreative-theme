<?php
// Only category archives have a designed template; anything else that routes
// here (date archives, future archive types) goes to the blog index rather
// than rendering an empty shell. Redirect before any output.
if ( ! is_category() ) {
	wp_safe_redirect( home_url( '/blog/' ), 301 );
	exit;
}

get_header();

get_template_part( 'template-parts/archive', 'category' );

get_footer();
