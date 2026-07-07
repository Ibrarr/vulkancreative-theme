<?php
/**
 * Landing Page — ACF Flexible Content field group.
 *
 * Registered in PHP (like the Gravity Forms pickers in gravity-forms.php)
 * rather than acf-json, because a large flexible_content group with nested
 * repeaters is far more reliable to build programmatically than to hand-author
 * as JSON (acf_add_local_field_group wires the layout/repeater parents for us).
 * The field NAMES inside a layout are local to that layout (ACF namespaces
 * them), so `heading_start` etc. repeat across blocks; only the field KEYS are
 * globally unique. Blocks are read by the page-landing-page.php dispatcher and
 * rendered by template-parts/blocks/{layout}.php.
 */

if ( ! function_exists( 'vc_lp_text' ) ) {
	function vc_lp_text( $key, $label, $name, $default = '', $width = '', $instructions = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'text',
			'instructions' => $instructions, 'required' => 0, 'default_value' => $default,
			'wrapper' => [ 'width' => $width, 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_textarea' ) ) {
	function vc_lp_textarea( $key, $label, $name, $default = '', $rows = 3, $instructions = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'textarea',
			'instructions' => $instructions, 'required' => 0, 'default_value' => $default,
			'rows' => $rows, 'new_lines' => '', 'wrapper' => [ 'width' => '', 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_wysiwyg' ) ) {
	function vc_lp_wysiwyg( $key, $label, $name, $instructions = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'wysiwyg',
			'instructions' => $instructions, 'required' => 0, 'tabs' => 'all',
			'toolbar' => 'basic', 'media_upload' => 0, 'delay' => 0,
			'wrapper' => [ 'width' => '', 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_number' ) ) {
	function vc_lp_number( $key, $label, $name, $default = '', $width = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'number',
			'required' => 0, 'default_value' => $default,
			'wrapper' => [ 'width' => $width, 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_truefalse' ) ) {
	function vc_lp_truefalse( $key, $label, $name, $default = 1, $instructions = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'true_false',
			'instructions' => $instructions, 'default_value' => $default, 'ui' => 1,
			'wrapper' => [ 'width' => '', 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_select' ) ) {
	function vc_lp_select( $key, $label, $name, $choices, $default, $width = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'select',
			'choices' => $choices, 'default_value' => $default, 'ui' => 0, 'return_format' => 'value',
			'wrapper' => [ 'width' => $width, 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_image' ) ) {
	function vc_lp_image( $key, $label, $name, $instructions = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'image',
			'instructions' => $instructions, 'return_format' => 'array', 'preview_size' => 'medium',
			'library' => 'all', 'wrapper' => [ 'width' => '', 'class' => '', 'id' => '' ],
		];
	}
}
if ( ! function_exists( 'vc_lp_heading' ) ) {
	// Three editable heading parts (start / red / end), composed by
	// vc_heading_parts_sub('heading', …) in the dispatcher.
	function vc_lp_heading( $kp, $start = '', $red = '', $end = '' ) {
		return [
			vc_lp_text( "field_{$kp}_hs", 'Heading (start)', 'heading_start', $start, '33', 'The red words carry the emphasis. Leave start or end blank if the heading opens or closes with the red words.' ),
			vc_lp_text( "field_{$kp}_hr", 'Heading (red)', 'heading_red', $red, '33' ),
			vc_lp_text( "field_{$kp}_he", 'Heading (end)', 'heading_end', $end, '34' ),
		];
	}
}
if ( ! function_exists( 'vc_lp_repeater' ) ) {
	function vc_lp_repeater( $key, $label, $name, $sub_fields, $button, $min = 0, $max = 0, $instructions = '' ) {
		return [
			'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'repeater',
			'instructions' => $instructions, 'layout' => 'block', 'pagination' => 0,
			'min' => $min, 'max' => $max, 'collapsed' => '', 'button_label' => $button,
			'sub_fields' => $sub_fields, 'wrapper' => [ 'width' => '', 'class' => '', 'id' => '' ],
		];
	}
}

add_action( 'acf/init', 'vc_register_landing_page_fields' );
function vc_register_landing_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$layouts = [];

	// --- HERO (dark both modes) ---
	$layouts['hero'] = [
		'key' => 'layout_lp_hero', 'name' => 'hero', 'label' => 'Hero', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_hero', 'Build something', 'that performs', '.' ),
			[
				vc_lp_textarea( 'field_lp_hero_sub', 'Sub-heading', 'subheading', 'A short, specific promise that tells the visitor exactly what they get and why it matters.', 3 ),
				vc_lp_text( 'field_lp_hero_pl', 'Primary button label', 'primary_label', 'Start a Project', '50' ),
				vc_lp_text( 'field_lp_hero_pt', 'Primary button target', 'primary_target', '#lp-cta', '50', 'An anchor (e.g. #lp-cta) or a URL.' ),
				vc_lp_text( 'field_lp_hero_sl', 'Secondary button label', 'secondary_label', '', '50', 'Leave blank to hide the second button.' ),
				vc_lp_text( 'field_lp_hero_st', 'Secondary button target', 'secondary_target', '', '50' ),
				vc_lp_text( 'field_lp_hero_note', 'Reassurance note', 'note', '', '', 'A one-line trust cue, e.g. "London agency · Built in-house · Measured by results".' ),
				vc_lp_text( 'field_lp_hero_mark', 'Outlined monument', 'mark_text', '', '', 'Optional oversized outlined word/number behind the hero (e.g. £0). Leave blank for none.' ),
				vc_lp_truefalse( 'field_lp_hero_logos', 'Show logo marquee', 'show_logos', 0, 'Scroll the site-wide "Worked with" logos across the bottom of the hero.' ),
			]
		),
	];

	// --- INTRO / rich text ---
	$layouts['intro'] = [
		'key' => 'layout_lp_intro', 'name' => 'intro', 'label' => 'Intro / Rich text', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_intro', 'The problem', 'worth solving', '.' ),
			[ vc_lp_wysiwyg( 'field_lp_intro_body', 'Body', 'body', 'A short editorial paragraph or two.' ) ]
		),
	];

	// --- STEPS / how it works ---
	$layouts['steps'] = [
		'key' => 'layout_lp_steps', 'name' => 'steps', 'label' => 'Steps / How it works', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_steps', 'How it', 'works', '.' ),
			[
				vc_lp_textarea( 'field_lp_steps_sub', 'Sub-heading', 'subheading', 'The whole thing in a few plain steps.', 2 ),
				vc_lp_repeater( 'field_lp_steps_rep', 'Steps', 'steps', [
					vc_lp_text( 'field_lp_steps_title', 'Title', 'title', '', '', '' ),
					vc_lp_textarea( 'field_lp_steps_desc', 'Description', 'description', '', 2 ),
				], 'Add Step', 0, 8 ),
			]
		),
	];

	// --- CHECKLIST / what you get ---
	$layouts['checklist'] = [
		'key' => 'layout_lp_checklist', 'name' => 'checklist', 'label' => 'Checklist / What you get', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_check', 'What you', 'get', '.' ),
			[
				vc_lp_textarea( 'field_lp_check_sub', 'Sub-heading', 'subheading', 'Everything below is part of the deal.', 2 ),
				vc_lp_text( 'field_lp_check_stamp', 'Row stamp', 'included_label', 'Included', '50', 'The quiet label stamped on each row.' ),
				vc_lp_repeater( 'field_lp_check_rep', 'Items', 'items', [
					vc_lp_text( 'field_lp_check_item', 'Item', 'item', '', '', '' ),
					vc_lp_textarea( 'field_lp_check_detail', 'Detail', 'detail', '', 2, 'Optional one-line explanation.' ),
				], 'Add Item', 0, 0 ),
				vc_lp_truefalse( 'field_lp_check_showtotal', 'Show total row', 'show_total', 0 ),
				vc_lp_text( 'field_lp_check_tlabel', 'Total label', 'total_label', 'You pay today:', '50' ),
				vc_lp_text( 'field_lp_check_tvalue', 'Total value', 'total_value', '£0', '50', 'Rendered as the big red display number.' ),
				vc_lp_textarea( 'field_lp_check_tnote', 'Total note', 'total_note', '', 2 ),
			]
		),
	];

	// --- COMPARISON ---
	$layouts['comparison'] = [
		'key' => 'layout_lp_comparison', 'name' => 'comparison', 'label' => 'Comparison', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_comp', 'The difference', 'that matters', '.' ),
			[
				vc_lp_textarea( 'field_lp_comp_note', 'Intro note', 'note', '', 2 ),
				vc_lp_text( 'field_lp_comp_ut', 'Left column title', 'usual_title', 'The usual way', '50' ),
				vc_lp_text( 'field_lp_comp_vt', 'Right column title', 'vulkan_title', 'The Vulkan way', '50' ),
				vc_lp_repeater( 'field_lp_comp_rep', 'Rows', 'rows', [
					vc_lp_textarea( 'field_lp_comp_usual', 'Usual', 'usual', '', 2 ),
					vc_lp_textarea( 'field_lp_comp_vulkan', 'Vulkan', 'vulkan', '', 2 ),
				], 'Add Row', 0, 0 ),
			]
		),
	];

	// --- STATS / results ---
	$layouts['stats'] = [
		'key' => 'layout_lp_stats', 'name' => 'stats', 'label' => 'Stats / Results', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_stats', 'The', 'results', '.' ),
			[
				vc_lp_textarea( 'field_lp_stats_sub', 'Sub-heading', 'subheading', '', 2 ),
				vc_lp_repeater( 'field_lp_stats_rep', 'Stats', 'stats', [
					vc_lp_text( 'field_lp_stats_num', 'Number', 'number', '', '40', 'e.g. 3.2 or 40' ),
					vc_lp_text( 'field_lp_stats_pre', 'Prefix', 'prefix', '', '30', 'e.g. +' ),
					vc_lp_text( 'field_lp_stats_suf', 'Suffix', 'suffix', '', '30', 'e.g. % or x' ),
					vc_lp_text( 'field_lp_stats_label', 'Label', 'label', '', '', '' ),
				], 'Add Stat', 0, 8 ),
			]
		),
	];

	// --- TESTIMONIALS ---
	$layouts['testimonials'] = [
		'key' => 'layout_lp_testimonials', 'name' => 'testimonials', 'label' => 'Testimonials', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_test', 'What our', 'clients say', '.' ),
			[
				vc_lp_select( 'field_lp_test_source', 'Source', 'source', [ 'cpt' => 'Testimonial library', 'manual' => 'Manual list below' ], 'cpt', '50' ),
				vc_lp_number( 'field_lp_test_count', 'How many', 'count', 6, '50' ),
				vc_lp_repeater( 'field_lp_test_rep', 'Manual testimonials', 'items', [
					vc_lp_textarea( 'field_lp_test_quote', 'Quote', 'quote', '', 3 ),
					vc_lp_text( 'field_lp_test_name', 'Name', 'name', '', '33' ),
					vc_lp_text( 'field_lp_test_role', 'Role', 'role', '', '33' ),
					vc_lp_text( 'field_lp_test_company', 'Company', 'company', '', '34' ),
					vc_lp_image( 'field_lp_test_photo', 'Photo', 'photo', '' ),
				], 'Add Testimonial', 0, 0 ),
			]
		),
	];

	// --- FAQ ---
	$layouts['faq'] = [
		'key' => 'layout_lp_faq', 'name' => 'faq', 'label' => 'FAQ', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_faq', '', 'FAQs', '.' ),
			[
				vc_lp_text( 'field_lp_faq_cl', 'Side CTA label', 'cta_label', '', '50', 'Optional button beside the questions.' ),
				vc_lp_text( 'field_lp_faq_ct', 'Side CTA target', 'cta_target', '#lp-cta', '50' ),
				vc_lp_repeater( 'field_lp_faq_rep', 'Questions', 'faqs', [
					vc_lp_text( 'field_lp_faq_q', 'Question', 'question', '', '', '' ),
					vc_lp_textarea( 'field_lp_faq_a', 'Answer', 'answer', '', 4 ),
				], 'Add Question', 0, 0 ),
			]
		),
	];

	// --- CTA (dark both modes) ---
	$layouts['cta'] = [
		'key' => 'layout_lp_cta', 'name' => 'cta', 'label' => 'CTA / Form', 'display' => 'block',
		'sub_fields' => array_merge(
			vc_lp_heading( 'lp_cta', 'Ready to', 'get started', '?' ),
			[
				vc_lp_textarea( 'field_lp_cta_sub', 'Sub-heading', 'subheading', 'Tell us what you need and we will come back to you within one working day.', 3 ),
				vc_lp_select( 'field_lp_cta_mode', 'Mode', 'mode', [ 'form' => 'Form', 'buttons' => 'Buttons' ], 'form', '50' ),
				vc_lp_text( 'field_lp_cta_pl', 'Button label (Buttons mode)', 'primary_label', 'Start a Project', '50' ),
				vc_lp_text( 'field_lp_cta_pt', 'Button target (Buttons mode)', 'primary_target', '/contact/', '50' ),
			]
		),
	];

	acf_add_local_field_group( [
		'key'    => 'group_lp_page',
		'title'  => 'Landing Page',
		'fields' => [
			vc_lp_text( 'field_lp_header_cta_label', 'Header CTA label', 'lp_header_cta_label', 'Get in Touch', '50', 'The single button in the slim header.' ),
			vc_lp_text( 'field_lp_header_cta_target', 'Header CTA target', 'lp_header_cta_target', '#lp-cta', '50', 'An anchor (e.g. #lp-cta) or a URL.' ),
			[
				'key'          => 'field_lp_sections',
				'label'        => 'Sections',
				'name'         => 'lp_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Add, reorder and repeat the blocks that make up this landing page.',
				'button_label' => 'Add Section',
				'layouts'      => $layouts,
			],
		],
		'location' => [
			[
				[ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-landing-page.php' ],
			],
		],
		'menu_order'      => 0,
		'position'        => 'acf_after_title',
		'style'           => 'seamless',
		'label_placement' => 'top',
		'hide_on_screen'  => [ 'the_content' ],
		'active'          => true,
		'show_in_rest'    => 0,
	] );
}
