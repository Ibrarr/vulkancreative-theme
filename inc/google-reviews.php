<?php

// Google reviews: the sitewide rating source (Global Settings > Reviews) and
// its accessor. Registered in PHP for the same reason as inc/gravity-forms.php
// and inc/service-list-fields.php: the options page already exists in the ACF
// database, so a hand-edited acf-json file would sit behind a manual Sync.
// Every surface (the rating chips, the footer credit) reads vc_google_reviews()
// only; the filter is the hook a future Google Places API fetch overrides, so
// no template changes for it. No AggregateRating/Review schema is output
// anywhere on purpose: Google ignores self-serving review markup and bars
// marking up ratings sourced from Google itself, so the profile link is the
// verification route (standing rule, Aug 2026).
add_action( 'acf/init', 'vc_register_google_reviews_fields' );
function vc_register_google_reviews_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( [
		'key'    => 'group_vc_google_reviews',
		'title'  => 'Reviews',
		'fields' => [
			[
				'key'           => 'field_vc_google_rating',
				'label'         => 'Google Rating',
				'name'          => 'google_rating',
				'type'          => 'text',
				'instructions'  => 'The overall rating on the Google Business Profile, e.g. 5.0.',
				'default_value' => '5.0',
				'wrapper'       => [ 'width' => '25' ],
			],
			[
				'key'          => 'field_vc_google_review_count',
				'label'        => 'Review Count',
				'name'         => 'google_review_count',
				'type'         => 'number',
				'instructions' => 'The current number of reviews on the profile.',
				'min'          => 0,
				'step'         => 1,
				'wrapper'      => [ 'width' => '25' ],
			],
			[
				'key'            => 'field_vc_google_rating_date',
				'label'          => 'Checked On',
				'name'           => 'google_rating_date',
				'type'           => 'date_picker',
				'instructions'   => 'When the rating and count were last checked; shown as the small "as of" line Google\'s guidelines ask for.',
				'display_format' => 'd/m/Y',
				'return_format'  => 'M Y',
				'first_day'      => 1,
				'wrapper'        => [ 'width' => '25' ],
			],
			[
				'key'          => 'field_vc_google_profile_url',
				'label'        => 'Profile Link',
				'name'         => 'google_profile_url',
				'type'         => 'url',
				'instructions' => 'The Google Business Profile share link the rating and footer credit point at. Leave empty to show the neutral unlinked rating.',
				'wrapper'      => [ 'width' => '25' ],
			],
		],
		'location' => [
			[ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'global-settings' ] ],
		],
		'menu_order'  => 10,
		'active'      => true,
		'description' => '',
	] );
}

function vc_google_reviews() {
	$data = [
		'rating' => get_field( 'google_rating', 'options' ) ?: '5.0',
		'count'  => (int) get_field( 'google_review_count', 'options' ),
		'url'    => get_field( 'google_profile_url', 'options' ) ?: '',
		'as_of'  => get_field( 'google_rating_date', 'options' ) ?: '',
	];
	return apply_filters( 'vc_google_reviews', $data );
}

// Editor-entered text can carry {rating} and {reviews} tokens (the homepage
// results stats use them), so the figure is typed once, on Global Settings.
function vc_review_tokens( $text ) {
	if ( ! is_string( $text ) || false === strpos( $text, '{' ) ) {
		return $text;
	}
	$gr = vc_google_reviews();
	return strtr( $text, [
		'{rating}'  => (string) $gr['rating'],
		'{reviews}' => (string) $gr['count'],
	] );
}
