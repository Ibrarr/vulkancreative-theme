<?php
/**
 * Programmatic publishing endpoint (vc/v1).
 *
 * The server side of the local-file publishing pipeline: a composite
 * create-or-update route keyed on type + slug that writes core fields, ACF
 * fields (names resolved to field keys against the live registry, per layout
 * for flexible content), taxonomy terms by slug, the Yoast title/description
 * and primary-term meta, then touches the post so Yoast rebuilds its
 * indexable. Validation runs in full before any write; a validation failure
 * writes nothing. Documents default to draft, and updates are non-destructive
 * unless overwrite is set, matching the retired seeders' behaviour.
 * Companions: GET /publish-schema serves the resolved per-type model for the
 * CLI's validation, and GET /media?hash= looks attachments up by source hash
 * so re-published images never duplicate.
 */

// Per-type publishing config: writable core fields, taxonomies terms may
// target, post-object fields that arrive as slugs in `refs` (mapped to the
// post type they point at), and required entries beyond ACF's own flags —
// the template hard gates (an imageless case study is invisible on every
// listing) and the blog fields whose absence renders an empty page.
function vc_publish_types() {
	return [
		'post'        => [
			'post_type'  => 'post',
			'core'       => [ 'excerpt', 'featured_media', 'author' ],
			'taxonomies' => [ 'category' ],
			'refs'       => [],
			'require'    => [ 'excerpt', 'featured_media', 'terms:category' ],
		],
		'project'     => [
			'post_type'  => 'project',
			'core'       => [ 'date' ],
			'taxonomies' => [ 'service' ],
			'refs'       => [ 'pj_case_study' => 'case_study' ],
			'require'    => [ 'terms:service' ],
		],
		'case_study'  => [
			'post_type'  => 'case_study',
			'core'       => [ 'date' ],
			'taxonomies' => [ 'service' ],
			'refs'       => [ 'cs_testimonial' => 'testimonial' ],
			'require'    => [ 'field:cs_image', 'terms:service' ],
		],
		'testimonial' => [
			'post_type'  => 'testimonial',
			'core'       => [],
			'taxonomies' => [],
			'refs'       => [],
			'require'    => [],
		],
		'page'        => [
			'post_type'  => 'page',
			'core'       => [ 'template' ],
			'taxonomies' => [],
			'refs'       => [],
			'require'    => [ 'field:lp_sections' ],
			'template'   => 'page-templates/page-landing-page.php',
		],
	];
}

function vc_publish_permission() {
	return current_user_can( 'manage_options' );
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'vc/v1', '/publish', [
		'methods'             => 'POST',
		'permission_callback' => 'vc_publish_permission',
		'callback'            => 'vc_publish_route',
	] );

	register_rest_route( 'vc/v1', '/publish-schema', [
		'methods'             => 'GET',
		'permission_callback' => 'vc_publish_permission',
		'callback'            => 'vc_publish_schema_route',
	] );

	register_rest_route( 'vc/v1', '/media', [
		'methods'             => 'GET',
		'permission_callback' => 'vc_publish_permission',
		'callback'            => 'vc_publish_media_route',
		'args'                => [
			'hash' => [ 'required' => true, 'sanitize_callback' => 'sanitize_text_field' ],
		],
	] );
} );

// The CLI stamps a sha256 of each source file on upload through the core
// media endpoint; protected meta needs the auth_callback to accept the write.
add_action( 'init', function () {
	register_post_meta( 'attachment', '_vc_source_hash', [
		'type'          => 'string',
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => function () {
			return current_user_can( 'upload_files' );
		},
	] );
} );

// Every ACF field visible on this type, keyed by field name. Walking the
// live registry (rather than a generated map) covers both the hashed
// acf-json keys and the PHP-registered landing group, and cannot drift.
function vc_publish_field_registry( $config ) {
	if ( ! function_exists( 'acf_get_field_groups' ) ) {
		return [];
	}

	$args = [ 'post_type' => $config['post_type'] ];
	if ( ! empty( $config['template'] ) ) {
		$args['page_template'] = $config['template'];
	}

	$registry = [];
	foreach ( acf_get_field_groups( $args ) as $group ) {
		$fields = acf_get_fields( $group );
		if ( ! $fields ) {
			continue;
		}
		foreach ( $fields as $field ) {
			$registry[ $field['name'] ] = $field;
		}
	}
	return $registry;
}

// Blank means "nothing to write": empty string, null or an empty array.
// false and 0 are values (true_false fields store 0).
function vc_publish_is_blank( $value ) {
	return '' === $value || null === $value || ( is_array( $value ) && ! $value );
}

function vc_publish_existing_is_empty( $field, $post_id ) {
	$raw = get_post_meta( $post_id, $field['name'], true );
	if ( 'repeater' === $field['type'] ) {
		return ! (int) $raw;
	}
	if ( 'flexible_content' === $field['type'] ) {
		return empty( $raw );
	}
	return '' === $raw || null === $raw || ( is_array( $raw ) && ! $raw );
}

// Validate one incoming value against its field definition and convert it to
// the keyed structure update_field() needs: repeater rows and flexible-content
// rows are re-keyed from sub-field names to sub-field keys (names repeat
// across layouts; only keys are unique).
function vc_publish_prepare_value( $field, $value, $path, &$errors ) {
	switch ( $field['type'] ) {
		case 'repeater':
			return vc_publish_prepare_rows( $field['sub_fields'], $value, $path, $errors, $field );

		case 'flexible_content':
			if ( ! is_array( $value ) ) {
				$errors[] = [ 'field' => $path, 'code' => 'bad_value', 'message' => 'Expected a list of section rows.' ];
				return null;
			}
			$layouts = [];
			foreach ( (array) $field['layouts'] as $layout ) {
				$layouts[ $layout['name'] ] = $layout;
			}
			$rows = [];
			foreach ( array_values( $value ) as $i => $row ) {
				$row_path = $path . '[' . $i . ']';
				if ( ! is_array( $row ) || empty( $row['layout'] ) ) {
					$errors[] = [ 'field' => $row_path, 'code' => 'bad_row', 'message' => 'Each section row needs a layout name.' ];
					continue;
				}
				$name = $row['layout'];
				if ( ! isset( $layouts[ $name ] ) ) {
					$errors[] = [ 'field' => $row_path, 'code' => 'bad_layout', 'message' => sprintf( 'Unknown layout %s. Known: %s.', $name, implode( ', ', array_keys( $layouts ) ) ) ];
					continue;
				}
				unset( $row['layout'] );
				$prepared = vc_publish_prepare_rows( $layouts[ $name ]['sub_fields'], [ $row ], $row_path, $errors );
				if ( isset( $prepared[0] ) ) {
					$rows[] = [ 'acf_fc_layout' => $name ] + $prepared[0];
				}
			}
			return $rows;

		case 'image':
			$id = (int) $value;
			$attachment = $id ? get_post( $id ) : null;
			if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
				$errors[] = [ 'field' => $path, 'code' => 'missing_attachment', 'message' => 'No attachment with ID ' . $id . '.' ];
				return null;
			}
			if ( ! empty( $field['mime_types'] ) ) {
				$allowed = array_map( 'trim', explode( ',', strtolower( $field['mime_types'] ) ) );
				$type    = wp_check_filetype( get_attached_file( $id ) );
				if ( empty( $type['ext'] ) || ! in_array( strtolower( $type['ext'] ), $allowed, true ) ) {
					$errors[] = [ 'field' => $path, 'code' => 'bad_mime', 'message' => sprintf( 'Attachment %d is %s; this field accepts %s.', $id, $type['ext'] ?: 'unknown', $field['mime_types'] ) ];
					return null;
				}
			}
			return $id;

		case 'post_object':
			$id     = (int) $value;
			$target = $id ? get_post( $id ) : null;
			$wanted = array_filter( (array) ( $field['post_type'] ?? [] ) );
			if ( ! $target || ( $wanted && ! in_array( $target->post_type, $wanted, true ) ) ) {
				$errors[] = [ 'field' => $path, 'code' => 'bad_reference', 'message' => 'No matching post with ID ' . $id . '.' ];
				return null;
			}
			return $id;

		case 'true_false':
			return empty( $value ) ? 0 : 1;

		case 'number':
			if ( ! is_numeric( $value ) ) {
				$errors[] = [ 'field' => $path, 'code' => 'bad_number', 'message' => 'Expected a number.' ];
				return null;
			}
			return $value;

		default:
			if ( is_array( $value ) ) {
				$errors[] = [ 'field' => $path, 'code' => 'bad_value', 'message' => 'Expected a single value, got a list.' ];
				return null;
			}
			return (string) $value;
	}
}

// Rows for a repeater (or one flexible-content row): validate sub-field
// names, enforce required sub-fields per row, re-key names to keys.
function vc_publish_prepare_rows( $sub_fields, $rows, $path, &$errors, $repeater = null ) {
	if ( ! is_array( $rows ) ) {
		$errors[] = [ 'field' => $path, 'code' => 'bad_value', 'message' => 'Expected a list of rows.' ];
		return null;
	}

	if ( $repeater ) {
		$count = count( $rows );
		if ( ! empty( $repeater['max'] ) && $count > (int) $repeater['max'] ) {
			$errors[] = [ 'field' => $path, 'code' => 'too_many_rows', 'message' => sprintf( '%d rows; the maximum is %d.', $count, $repeater['max'] ) ];
			return null;
		}
	}

	$by_name = [];
	foreach ( (array) $sub_fields as $sub ) {
		$by_name[ $sub['name'] ] = $sub;
	}

	$out = [];
	foreach ( array_values( $rows ) as $i => $row ) {
		$row_path = $repeater ? $path . '[' . $i . ']' : $path;
		if ( ! is_array( $row ) ) {
			$errors[] = [ 'field' => $row_path, 'code' => 'bad_row', 'message' => 'Expected an object of sub-field values.' ];
			continue;
		}
		$keyed = [];
		foreach ( $row as $name => $value ) {
			if ( ! isset( $by_name[ $name ] ) ) {
				$errors[] = [ 'field' => $row_path . '.' . $name, 'code' => 'unknown_field', 'message' => sprintf( 'Unknown sub-field. Known: %s.', implode( ', ', array_keys( $by_name ) ) ) ];
				continue;
			}
			$keyed[ $by_name[ $name ]['key'] ] = vc_publish_prepare_value( $by_name[ $name ], $value, $row_path . '.' . $name, $errors );
		}
		foreach ( $by_name as $name => $sub ) {
			if ( ! empty( $sub['required'] ) && vc_publish_is_blank( $row[ $name ] ?? null ) ) {
				$errors[] = [ 'field' => $row_path . '.' . $name, 'code' => 'required', 'message' => 'Required in every row.' ];
			}
		}
		$out[] = $keyed;
	}
	return $out;
}

// A short human-readable view of a field's current value for dry-run diffs.
function vc_publish_current_snapshot( $field, $post_id ) {
	if ( ! $post_id ) {
		return null;
	}
	$raw = get_post_meta( $post_id, $field['name'], true );
	if ( 'repeater' === $field['type'] ) {
		return ( (int) $raw ) . ' rows';
	}
	if ( 'flexible_content' === $field['type'] ) {
		return count( (array) $raw ) . ' sections';
	}
	if ( is_array( $raw ) ) {
		return count( $raw ) . ' items';
	}
	$raw = (string) $raw;
	return strlen( $raw ) > 80 ? substr( $raw, 0, 77 ) . '...' : $raw;
}

function vc_publish_find_post( $slug, $post_type ) {
	$found = get_posts( [
		'name'           => $slug,
		'post_type'      => $post_type,
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	] );
	return $found ? $found[0] : null;
}

function vc_publish_route( WP_REST_Request $request ) {
	$payload = $request->get_json_params();
	if ( ! is_array( $payload ) ) {
		return new WP_Error( 'vc_publish_bad_json', 'The request body must be a JSON object.', [ 'status' => 400 ] );
	}
	return vc_publish_document( $payload );
}

function vc_publish_document( array $payload ) {
	$types = vc_publish_types();
	$type  = $payload['type'] ?? '';
	if ( ! isset( $types[ $type ] ) ) {
		return new WP_Error( 'vc_publish_bad_type', 'Unknown type: ' . $type, [ 'status' => 400, 'known' => array_keys( $types ) ] );
	}
	$config = $types[ $type ];

	if ( ! function_exists( 'update_field' ) ) {
		return new WP_Error( 'vc_publish_no_acf', 'ACF is not active.', [ 'status' => 500 ] );
	}

	$slug = (string) ( $payload['slug'] ?? '' );
	if ( '' === $slug || sanitize_title( $slug ) !== $slug ) {
		return new WP_Error( 'vc_publish_bad_slug', 'Slug must be lowercase letters, numbers and hyphens.', [ 'status' => 400 ] );
	}

	$overwrite = ! empty( $payload['overwrite'] );
	$dry       = ! empty( $payload['dry_run'] );
	$errors    = [];
	$warnings  = [];
	$report    = [];

	$known_keys = [ 'type', 'slug', 'title', 'status', 'overwrite', 'dry_run', 'core', 'fields', 'refs', 'terms', 'primary_terms', 'seo' ];
	foreach ( array_diff( array_keys( $payload ), $known_keys ) as $stray ) {
		$warnings[] = sprintf( 'Ignored unknown key "%s". Known keys: %s.', $stray, implode( ', ', $known_keys ) );
	}

	$existing = vc_publish_find_post( $slug, $config['post_type'] );

	$title = trim( (string) ( $payload['title'] ?? '' ) );
	if ( ! $existing && '' === $title ) {
		$errors[] = [ 'field' => 'title', 'code' => 'required', 'message' => 'A title is required to create a new entry.' ];
	}

	$status = $payload['status'] ?? null;
	if ( null !== $status && ! in_array( $status, [ 'draft', 'publish', 'pending' ], true ) ) {
		$errors[] = [ 'field' => 'status', 'code' => 'bad_status', 'message' => 'Status must be draft, publish or pending.' ];
	}

	$registry = vc_publish_field_registry( $config );
	if ( ! $registry ) {
		return new WP_Error( 'vc_publish_no_registry', 'No ACF field groups resolved for this type.', [ 'status' => 500 ] );
	}

	// Cross-references arrive as slugs and resolve to post IDs before the
	// ordinary field validation sees them.
	$fields = isset( $payload['fields'] ) && is_array( $payload['fields'] ) ? $payload['fields'] : [];
	$refs   = isset( $payload['refs'] ) && is_array( $payload['refs'] ) ? $payload['refs'] : [];
	foreach ( $refs as $name => $ref_slug ) {
		if ( ! isset( $config['refs'][ $name ] ) ) {
			$errors[] = [ 'field' => 'refs.' . $name, 'code' => 'bad_ref', 'message' => 'Not a reference field on ' . $type . '.' ];
			continue;
		}
		$ref_type = $config['refs'][ $name ];
		$target   = vc_publish_find_post( sanitize_title( (string) $ref_slug ), $ref_type );
		if ( ! $target ) {
			$errors[] = [ 'field' => 'refs.' . $name, 'code' => 'missing_ref', 'message' => sprintf( 'No %s found with slug "%s". Publish it first.', $ref_type, $ref_slug ) ];
			continue;
		}
		if ( 'publish' !== $target->post_status ) {
			$warnings[] = sprintf( '%s: the linked %s is %s; its section will not render until it is published.', $name, $ref_type, $target->post_status );
		}
		$fields[ $name ] = $target->ID;
	}

	$prepared = [];
	foreach ( $fields as $name => $value ) {
		if ( ! isset( $registry[ $name ] ) ) {
			$errors[] = [ 'field' => $name, 'code' => 'unknown_field', 'message' => 'No such field on ' . $type . '.' ];
			continue;
		}
		if ( 'select' === $registry[ $name ]['type'] && ! vc_publish_is_blank( $value ) ) {
			$choices = array_filter( array_keys( (array) ( $registry[ $name ]['choices'] ?? [] ) ), 'strlen' );
			if ( count( $choices ) > 1 && ! in_array( (string) $value, array_map( 'strval', $choices ), true ) ) {
				$warnings[] = sprintf( '%s: "%s" is not among the registered choices (%s).', $name, $value, implode( ', ', $choices ) );
			}
		}
		$prepared[ $name ] = vc_publish_prepare_value( $registry[ $name ], $value, $name, $errors );
	}

	// Terms resolve by slug and are never auto-created: categories and
	// services are curated structures, so a typo fails loudly.
	$term_ids   = [];
	$term_slugs = [];
	$terms_in   = isset( $payload['terms'] ) && is_array( $payload['terms'] ) ? $payload['terms'] : [];
	foreach ( $terms_in as $tax => $slugs ) {
		if ( ! in_array( $tax, $config['taxonomies'], true ) ) {
			$errors[] = [ 'field' => 'terms.' . $tax, 'code' => 'bad_taxonomy', 'message' => sprintf( '%s does not take %s terms.', $type, $tax ) ];
			continue;
		}
		foreach ( (array) $slugs as $term_slug ) {
			$term = get_term_by( 'slug', $term_slug, $tax );
			if ( ! $term ) {
				$errors[] = [ 'field' => 'terms.' . $tax, 'code' => 'missing_term', 'message' => sprintf( 'No %s term with slug "%s". Terms are never auto-created.', $tax, $term_slug ) ];
				continue;
			}
			$term_ids[ $tax ][]   = $term->term_id;
			$term_slugs[ $tax ][] = $term->slug;
		}
	}

	$primaries  = [];
	$primary_in = isset( $payload['primary_terms'] ) && is_array( $payload['primary_terms'] ) ? $payload['primary_terms'] : [];
	foreach ( $primary_in as $tax => $primary_slug ) {
		if ( empty( $term_slugs[ $tax ] ) ) {
			$errors[] = [ 'field' => 'primary_terms.' . $tax, 'code' => 'no_terms', 'message' => 'A primary term needs the terms list for the same taxonomy in this payload.' ];
			continue;
		}
		$position = array_search( $primary_slug, $term_slugs[ $tax ], true );
		if ( false === $position ) {
			$errors[] = [ 'field' => 'primary_terms.' . $tax, 'code' => 'not_assigned', 'message' => sprintf( '"%s" is not among the assigned %s terms.', $primary_slug, $tax ) ];
			continue;
		}
		$primaries[ $tax ] = $term_ids[ $tax ][ $position ];
	}

	// Core extras, gated per type.
	$core = isset( $payload['core'] ) && is_array( $payload['core'] ) ? $payload['core'] : [];
	foreach ( array_diff( array_keys( $core ), $config['core'] ) as $stray ) {
		$errors[] = [ 'field' => 'core.' . $stray, 'code' => 'bad_core', 'message' => sprintf( '%s does not take core.%s.', $type, $stray ) ];
	}

	$featured = isset( $core['featured_media'] ) ? (int) $core['featured_media'] : 0;
	if ( $featured ) {
		$attachment = get_post( $featured );
		if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
			$errors[] = [ 'field' => 'core.featured_media', 'code' => 'missing_attachment', 'message' => 'No attachment with ID ' . $featured . '.' ];
		} elseif ( ! wp_attachment_is_image( $featured ) ) {
			$errors[] = [ 'field' => 'core.featured_media', 'code' => 'not_an_image', 'message' => 'Attachment ' . $featured . ' is not an image.' ];
		}
	}

	$author = isset( $core['author'] ) ? (int) $core['author'] : 0;
	if ( $author && ! get_user_by( 'id', $author ) ) {
		$errors[] = [ 'field' => 'core.author', 'code' => 'missing_user', 'message' => 'No user with ID ' . $author . '.' ];
	}

	// Placement: a go-live date (YYYY-MM-DD) sets post_date, which orders the
	// /work/ and /case-studies/ archives and the service-page strips. It is a
	// placement instruction rather than editor content, so it applies on every
	// run, overwrite or not, and a re-run can move an entry.
	$date_local = '';
	if ( in_array( 'date', $config['core'], true ) && ! vc_publish_is_blank( $core['date'] ?? null ) ) {
		$date_in = trim( (string) $core['date'] );
		$parsed  = DateTimeImmutable::createFromFormat( '!Y-m-d', $date_in, wp_timezone() );
		if ( ! $parsed || $parsed->format( 'Y-m-d' ) !== $date_in ) {
			$errors[] = [ 'field' => 'core.date', 'code' => 'bad_date', 'message' => 'Expected YYYY-MM-DD.' ];
		} elseif ( $parsed->getTimestamp() > time() ) {
			$errors[] = [ 'field' => 'core.date', 'code' => 'future_date', 'message' => 'The date is in the future; WordPress would schedule the post instead of publishing it.' ];
		} else {
			$date_local = $parsed->format( 'Y-m-d' ) . ' 09:00:00';
		}
	}

	$template = null;
	if ( 'page' === $type ) {
		$template       = ! empty( $core['template'] ) ? (string) $core['template'] : $config['template'];
		$page_templates = wp_get_theme()->get_page_templates( null, 'page' );
		if ( ! isset( $page_templates[ $template ] ) ) {
			$errors[] = [ 'field' => 'core.template', 'code' => 'bad_template', 'message' => sprintf( 'Unknown template %s. Known: %s.', $template, implode( ', ', array_keys( $page_templates ) ) ) ];
		}
	}

	// Hard requirements: ACF's own required flags plus the per-type extras.
	// Satisfied by a non-blank incoming value, or by an existing non-empty
	// value on an update.
	$field_ok = function ( $name ) use ( $prepared, $existing, $registry ) {
		if ( isset( $prepared[ $name ] ) && ! vc_publish_is_blank( $prepared[ $name ] ) ) {
			return true;
		}
		return $existing && isset( $registry[ $name ] ) && ! vc_publish_existing_is_empty( $registry[ $name ], $existing->ID );
	};

	foreach ( $registry as $name => $field ) {
		if ( ! empty( $field['required'] ) && ! $field_ok( $name ) ) {
			$errors[] = [ 'field' => $name, 'code' => 'required', 'message' => 'Required for ' . $type . '.' ];
		}
	}

	foreach ( $config['require'] as $requirement ) {
		if ( 0 === strpos( $requirement, 'field:' ) ) {
			$name = substr( $requirement, 6 );
			if ( ! $field_ok( $name ) ) {
				$errors[] = [ 'field' => $name, 'code' => 'required', 'message' => 'Required for ' . $type . ': the templates gate on it.' ];
			}
		} elseif ( 0 === strpos( $requirement, 'terms:' ) ) {
			$tax = substr( $requirement, 6 );
			$has = ! empty( $term_ids[ $tax ] ) || ( $existing && wp_get_object_terms( $existing->ID, $tax, [ 'fields' => 'ids' ] ) );
			if ( ! $has ) {
				$errors[] = [ 'field' => 'terms.' . $tax, 'code' => 'required', 'message' => sprintf( 'At least one %s term is required.', $tax ) ];
			}
		} elseif ( 'excerpt' === $requirement ) {
			if ( vc_publish_is_blank( $core['excerpt'] ?? null ) && ( ! $existing || '' === $existing->post_excerpt ) ) {
				$errors[] = [ 'field' => 'core.excerpt', 'code' => 'required', 'message' => 'An explicit excerpt is required: the hero standfirst and archive cards read it, and there is no body to derive one from.' ];
			}
		} elseif ( 'featured_media' === $requirement ) {
			if ( ! $featured && ( ! $existing || ! has_post_thumbnail( $existing->ID ) ) ) {
				$errors[] = [ 'field' => 'core.featured_media', 'code' => 'required', 'message' => 'A featured image is required: the hero and archive cards read it.' ];
			}
		}
	}

	if ( $errors ) {
		return new WP_Error( 'vc_publish_validation', 'Validation failed; nothing was written.', [
			'status'   => 400,
			'errors'   => $errors,
			'warnings' => $warnings,
		] );
	}

	// ------------------------------------------------------------------
	// Dry run: the full per-field verdict, nothing written.
	// ------------------------------------------------------------------
	if ( $dry ) {
		if ( ! $existing ) {
			$report['post'] = 'would create as ' . ( $status ?: 'draft' );
		} else {
			$report['post'] = 'exists (' . $existing->post_status . '); would update';
		}
		if ( $date_local ) {
			$report['date'] = ( $existing && substr( $existing->post_date, 0, 10 ) === substr( $date_local, 0, 10 ) ) ? 'unchanged' : 'would set ' . substr( $date_local, 0, 10 );
		}
		foreach ( $prepared as $name => $value ) {
			$field = $registry[ $name ];
			if ( vc_publish_is_blank( $value ) ) {
				$report[ $name ] = 'would skip (empty incoming)';
			} elseif ( $existing && ! $overwrite && ! vc_publish_existing_is_empty( $field, $existing->ID ) ) {
				$report[ $name ] = 'would keep existing (currently: ' . vc_publish_current_snapshot( $field, $existing->ID ) . ')';
			} else {
				$report[ $name ] = 'would set';
			}
		}
		foreach ( $term_slugs as $tax => $slugs ) {
			$report[ 'terms.' . $tax ] = 'would set (' . implode( ', ', $slugs ) . ')';
		}
		return rest_ensure_response( [
			'dry_run'      => true,
			'type'         => $type,
			'slug'         => $slug,
			'would_create' => ! $existing,
			'report'       => $report,
			'warnings'     => $warnings,
		] );
	}

	// ------------------------------------------------------------------
	// Write.
	// ------------------------------------------------------------------
	$created = false;
	if ( ! $existing ) {
		$postarr = [
			'post_type'   => $config['post_type'],
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => $status ?: 'draft',
		];
		if ( ! vc_publish_is_blank( $core['excerpt'] ?? null ) ) {
			$postarr['post_excerpt'] = (string) $core['excerpt'];
		}
		if ( $author ) {
			$postarr['post_author'] = $author;
		}
		if ( $template ) {
			$postarr['page_template'] = $template;
		}
		if ( $date_local ) {
			$postarr['post_date']     = $date_local;
			$postarr['post_date_gmt'] = get_gmt_from_date( $date_local );
		}
		$post_id = wp_insert_post( wp_slash( $postarr ), true );
		if ( is_wp_error( $post_id ) ) {
			$post_id->add_data( [ 'status' => 500 ] );
			return $post_id;
		}
		$created        = true;
		$report['post'] = 'created as ' . $postarr['post_status'];
	} else {
		$post_id = $existing->ID;
		$delta   = [];

		if ( '' !== $title && $title !== $existing->post_title ) {
			if ( $overwrite ) {
				$delta['post_title'] = $title;
				$report['title']     = 'set';
			} else {
				$report['title'] = 'kept existing (overwrite off)';
			}
		}
		if ( null !== $status && $status !== $existing->post_status ) {
			$delta['post_status'] = $status;
			$report['status']     = 'set to ' . $status;
		}
		if ( ! vc_publish_is_blank( $core['excerpt'] ?? null ) && (string) $core['excerpt'] !== $existing->post_excerpt ) {
			if ( $overwrite || '' === $existing->post_excerpt ) {
				$delta['post_excerpt'] = (string) $core['excerpt'];
				$report['excerpt']     = 'set';
			} else {
				$report['excerpt'] = 'kept existing (overwrite off)';
			}
		}
		if ( $author && $author !== (int) $existing->post_author ) {
			if ( $overwrite ) {
				$delta['post_author'] = $author;
				$report['author']     = 'set';
			} else {
				$report['author'] = 'kept existing (overwrite off)';
			}
		}
		if ( $template ) {
			$current_template = get_page_template_slug( $post_id );
			if ( $template !== $current_template ) {
				if ( $overwrite || ! $current_template || 'default' === $current_template ) {
					$delta['page_template'] = $template;
					$report['template']     = 'set';
				} else {
					$report['template'] = 'kept existing (overwrite off)';
				}
			}
		}
		if ( $date_local && substr( $existing->post_date, 0, 10 ) !== substr( $date_local, 0, 10 ) ) {
			$delta['post_date']     = $date_local;
			$delta['post_date_gmt'] = get_gmt_from_date( $date_local );
			$delta['edit_date']     = true; // drafts otherwise get post_date reset to now on update
			$report['date']         = 'set to ' . substr( $date_local, 0, 10 );
		}
		if ( $delta ) {
			$delta['ID'] = $post_id;
			$updated     = wp_update_post( wp_slash( $delta ), true );
			if ( is_wp_error( $updated ) ) {
				$updated->add_data( [ 'status' => 500 ] );
				return $updated;
			}
		}
		if ( ! isset( $report['post'] ) ) {
			$report['post'] = 'updated';
		}
	}

	if ( $featured ) {
		if ( ! $created && ! $overwrite && has_post_thumbnail( $post_id ) ) {
			$report['featured_media'] = 'kept existing (overwrite off)';
		} else {
			set_post_thumbnail( $post_id, $featured );
			$report['featured_media'] = 'set (attachment ' . $featured . ')';
		}
	}

	foreach ( $prepared as $name => $value ) {
		$field = $registry[ $name ];
		if ( vc_publish_is_blank( $value ) ) {
			$report[ $name ] = 'skipped (empty incoming)';
			continue;
		}
		if ( ! $created && ! $overwrite && ! vc_publish_existing_is_empty( $field, $post_id ) ) {
			$report[ $name ] = 'kept existing';
			continue;
		}
		// Pre-slash: update_metadata unslashes once, and ACF does not slash
		// for us, so an unslashed literal backslash would be eaten.
		update_field( $field['key'], wp_slash( $value ), $post_id );
		$report[ $name ] = 'set';
	}

	foreach ( $term_ids as $tax => $ids ) {
		if ( ! $created && ! $overwrite && wp_get_object_terms( $post_id, $tax, [ 'fields' => 'ids' ] ) ) {
			$report[ 'terms.' . $tax ] = 'kept existing';
			continue;
		}
		$set = wp_set_object_terms( $post_id, $ids, $tax );
		$report[ 'terms.' . $tax ] = is_wp_error( $set ) ? 'failed: ' . $set->get_error_message() : 'set (' . implode( ', ', $term_slugs[ $tax ] ) . ')';
	}

	foreach ( $primaries as $tax => $term_id ) {
		$meta_key = '_yoast_wpseo_primary_' . $tax;
		if ( ! $created && ! $overwrite && '' !== (string) get_post_meta( $post_id, $meta_key, true ) ) {
			$report[ 'primary.' . $tax ] = 'kept existing';
			continue;
		}
		update_post_meta( $post_id, $meta_key, (string) $term_id );
		$report[ 'primary.' . $tax ] = 'set';
	}

	$seo     = isset( $payload['seo'] ) && is_array( $payload['seo'] ) ? $payload['seo'] : [];
	$seo_map = [ 'title' => '_yoast_wpseo_title', 'description' => '_yoast_wpseo_metadesc' ];
	foreach ( $seo_map as $in => $meta_key ) {
		if ( vc_publish_is_blank( $seo[ $in ] ?? null ) ) {
			continue;
		}
		if ( ! $created && ! $overwrite && '' !== (string) get_post_meta( $post_id, $meta_key, true ) ) {
			$report[ 'seo.' . $in ] = 'kept existing';
			continue;
		}
		update_post_meta( $post_id, $meta_key, wp_slash( (string) $seo[ $in ] ) );
		$report[ 'seo.' . $in ] = 'set';
	}

	// A no-op save so Yoast rebuilds this post's indexable after the meta
	// writes it never saw (the house precedent from the seeders).
	wp_update_post( [ 'ID' => $post_id ] );

	return rest_ensure_response( [
		'id'           => $post_id,
		'created'      => $created,
		'type'         => $type,
		'slug'         => $slug,
		'status'       => get_post_status( $post_id ),
		'link'         => get_permalink( $post_id ),
		'preview_link' => get_preview_post_link( $post_id ),
		'report'       => $report,
		'warnings'     => $warnings,
	] );
}

// A trimmed field definition for the schema route: what the CLI needs to
// validate and map source files, nothing else.
function vc_publish_trim_field( $field ) {
	$out = [
		'name'     => $field['name'],
		'type'     => $field['type'],
		'required' => ! empty( $field['required'] ),
	];
	foreach ( [ 'mime_types', 'min', 'max' ] as $extra ) {
		if ( ! empty( $field[ $extra ] ) ) {
			$out[ $extra ] = $field[ $extra ];
		}
	}
	if ( 'select' === $field['type'] && ! empty( $field['choices'] ) ) {
		$out['choices'] = array_keys( (array) $field['choices'] );
	}
	if ( 'post_object' === $field['type'] && ! empty( $field['post_type'] ) ) {
		$out['post_type'] = array_values( (array) $field['post_type'] );
	}
	if ( ! empty( $field['sub_fields'] ) ) {
		$out['sub_fields'] = array_map( 'vc_publish_trim_field', array_values( $field['sub_fields'] ) );
	}
	if ( ! empty( $field['layouts'] ) ) {
		$out['layouts'] = [];
		foreach ( (array) $field['layouts'] as $layout ) {
			$out['layouts'][] = [
				'name'   => $layout['name'],
				'fields' => array_map( 'vc_publish_trim_field', array_values( $layout['sub_fields'] ) ),
			];
		}
	}
	return $out;
}

function vc_publish_schema_route() {
	$out = [];
	foreach ( vc_publish_types() as $type => $config ) {
		$taxonomies = [];
		foreach ( $config['taxonomies'] as $tax ) {
			$terms = get_terms( [ 'taxonomy' => $tax, 'hide_empty' => false ] );
			$taxonomies[ $tax ] = [];
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$taxonomies[ $tax ][] = [
						'slug'   => $term->slug,
						'name'   => $term->name,
						'parent' => (int) $term->parent,
					];
				}
			}
		}
		$entry = [
			'post_type'  => $config['post_type'],
			'core'       => $config['core'],
			'refs'       => $config['refs'],
			'require'    => $config['require'],
			'taxonomies' => $taxonomies,
			'fields'     => array_map( 'vc_publish_trim_field', array_values( vc_publish_field_registry( $config ) ) ),
		];
		if ( ! empty( $config['template'] ) ) {
			$entry['template'] = $config['template'];
		}
		$out[ $type ] = $entry;
	}

	$forms = [];
	if ( class_exists( 'GFAPI' ) ) {
		foreach ( GFAPI::get_forms() as $form ) {
			$forms[] = [ 'id' => (int) $form['id'], 'title' => $form['title'] ];
		}
	}
	$out['_environment'] = [
		'templates' => array_keys( wp_get_theme()->get_page_templates( null, 'page' ) ),
		'forms'     => $forms,
	];

	return rest_ensure_response( $out );
}

function vc_publish_media_route( WP_REST_Request $request ) {
	$found = get_posts( [
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 1,
		'no_found_rows'  => true,
		'meta_key'       => '_vc_source_hash',
		'meta_value'     => $request->get_param( 'hash' ),
	] );
	if ( ! $found ) {
		return rest_ensure_response( [ 'found' => false ] );
	}
	return rest_ensure_response( [
		'found' => true,
		'id'    => $found[0]->ID,
		'url'   => wp_get_attachment_url( $found[0]->ID ),
		'mime'  => $found[0]->post_mime_type,
	] );
}
