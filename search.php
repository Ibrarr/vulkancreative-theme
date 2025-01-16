<?php
get_header();
$term = get_search_query();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
	's' => $term,
	'posts_per_page' => 8,
	'paged' => $paged,
	'post_type' => ['post', 'video'],
);
$query = new WP_Query($args);
?>



<?php
get_footer();
?>