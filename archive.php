<?php
get_header();

if (is_category()) {
    get_template_part( 'template-parts/archive', 'category' );
}

if ( is_tax() ) {
	$taxonomy = get_query_var( 'taxonomy' );
	switch ( $taxonomy ) {
		case 'practice_area':
            $term = get_queried_object();
            if ( $term->parent == 0 ) {
                get_template_part( 'template-parts/archive', 'practice-area-parent' );
            } else {
                get_template_part( 'template-parts/archive', 'practice-area-child' );
            }
			break;
		default:
			wp_redirect( home_url() );
			break;
	}
}



if ( is_post_type_archive() ) {
	switch ( get_post_type() ) {
		case 'case_study':
			get_template_part( 'template-parts/archive', 'case-study' );
			break;
		default:
			wp_redirect( home_url() );
			break;
	}
}


get_footer();