<?php

/**
 * Update the post author with the one selected from the ACF field
 */
add_action( 'acf/save_post', 'update_author_to_acf_field', 20 );
function update_author_to_acf_field( $post_id ) {
	$user = get_field( 'author', $post_id );
	if ( $user ) {
		wp_update_post( array( 'ID' => $post_id, 'post_author' => $user['ID'] ) );
	}
}

/**
 * Directory to save ACF fields to
 */
add_filter( 'acf/settings/save_json', 'my_acf_json_save_point' );
function my_acf_json_save_point( $path ) {
	return VC_TEMPLATE_DIR . '/acf-json';
}

/**
 * Make the file save as the name of the field
 */
add_filter( 'acf/json/save_file_name', 'custom_acf_json_filename', 10, 3 );
function custom_acf_json_filename( $filename, $post, $load_path ) {
	$filename = str_replace(
		array(
			' ',
			'_',
		),
		array(
			'-',
			'-'
		),
		$post['title']
	);

	$filename = strtolower( $filename ) . '.json';

	return $filename;
}

/**
 * Where to load ACF fields from
 */
add_filter( 'acf/settings/load_json', 'my_acf_json_load_point' );
function my_acf_json_load_point( $paths ) {
	// Remove the original path (optional).
	unset( $paths[0] );

	// Append the new path and return it.
	$paths[] = VC_TEMPLATE_DIR . '/acf-json';

	return $paths;
}

