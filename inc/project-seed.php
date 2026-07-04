<?php

/**
 * Settings > Seed Work: one-click seeding of the per-project showcase content
 * (year, overview statement and support, deliverables and the Yoast search
 * description) so a fresh environment does not need every field typed in by
 * hand. Projects are matched by SLUG, never by ID, so it works on staging and
 * production where post IDs differ. By default only empty fields are filled —
 * existing edits are never touched unless "overwrite" is ticked. The 'fix'
 * entries are the one exception: they repair known-incoherent sample base
 * data (a sector or description that belonged to another sample) and always
 * apply. Gallery and hero imagery is attached separately (media libraries
 * differ per environment); the captions here are the canonical caption copy
 * that the image-attach step reads.
 */

add_action( 'admin_menu', 'vc_seed_work_menu' );
function vc_seed_work_menu() {
	add_options_page(
		'Seed Work',
		'Seed Work',
		'manage_options',
		'vc-seed-work',
		'vc_seed_work_page'
	);
}

/**
 * The launch content, keyed by post slug. All of it is [SAMPLE] fictional
 * client copy following the site style guide; field keys belong to the
 * "Project Details" ACF group (acf-json/project-details.json).
 */
function vc_seed_work_content() {
	return [
		'sample-harland-vale' => [
			'year'         => '2025',
			'statement'    => 'Harland & Vale sell family homes off plan, so the brand and the website had to do the persuading before a single show home opened.',
			'support'      => 'We built the identity from the ground up, then designed and developed a WordPress site around the two questions every buyer asks: where are the homes, and can we trust the people building them? Development pages lead with plans, prices and timelines, and every page ends with a clear next step to the sales team.',
			'deliverables' => [ 'Brand identity', 'Art direction', 'WordPress design and build', 'Development listings system', 'Local SEO setup', 'Analytics and enquiry tracking' ],
			'captions'     => [
				'The development page template: plans, prices and timelines up front.',
				'A wordmark and palette built to sit as comfortably on site hoardings as on screen.',
				'Every page ends with a clear route to the sales team.',
			],
			'seo_desc'     => 'Brand identity and a WordPress website for Harland & Vale, a Surrey property developer selling family homes off plan.',
		],
		'sample-vulkan-creative' => [
			'fix'          => [
				'pj_sector'      => 'Agency',
				'pj_description' => 'Design and build of our own studio site, dark-first and 3D-led.',
			],
			'year'         => '2026',
			'statement'    => 'Our own shop window had to prove the point: a site that shows the craft before a word is read.',
			'support'      => 'We treated ourselves as the client: a dark-first design system, a 3D forge scene in the hero and every section animated with the same care we sell. It is the fastest way to judge us, because it is our own work.',
			'deliverables' => [ 'Brand refresh', 'Dark-first design system', 'WordPress design and build', '3D hero scene', 'Motion and interaction design', 'Technical SEO setup' ],
			'captions'     => [
				'The dark-first homepage with the 3D forge scene in the hero.',
				'A design system built section by section, light and dark from day one.',
				'Case studies and services wired for enquiries, not just browsing.',
			],
			'seo_desc'     => 'The Vulkan Creative studio site: a dark-first WordPress design and build with a 3D hero, designed and developed in-house.',
		],
		'sample-bexley-rowe-solicitors' => [
			'year'         => '2024',
			'statement'    => 'Bexley Rowe needed to be the family law firm local people actually find, not the one with the biggest ad budget.',
			'support'      => 'We rebuilt their content around the questions clients ask at the hardest moments: divorce, custody, wills. Plain-English guides, local landing pages and technical SEO fixes took them from page three to the results that matter.',
			'deliverables' => [ 'Content strategy', 'Plain-English legal guides', 'Local landing pages', 'Technical SEO fixes', 'Google Business Profile overhaul', 'Monthly reporting' ],
			'captions'     => [
				'Practice-area guides written in plain English, structured for search.',
				'Local pages that put each office on the map for its own town.',
				'Rankings and enquiries reported side by side, month by month.',
			],
			'seo_desc'     => 'Local SEO and a plain-English content programme for Bexley Rowe Solicitors, a family law firm.',
		],
		'sample-forge-field-fitness' => [
			'year'         => '2025',
			'statement'    => 'Forge & Field sell serious gym kit; the old site made it feel like a spreadsheet.',
			'support'      => 'We rebuilt the store around the products: big photography, honest specs and a checkout that gets out of the way. Paid search and paid social then sent buyers straight to the racks, plates and rigs they searched for.',
			'deliverables' => [ 'Ecommerce design and build', 'Product photography direction', 'Checkout optimisation', 'Google Shopping campaigns', 'Paid social campaigns', 'Conversion tracking' ],
			'captions'     => [
				'Product pages that lead with the kit: photography, specs, price, buy.',
				'The range page built for comparison shopping without the clutter.',
				'Shopping campaigns matched to the exact products people search for.',
			],
			'seo_desc'     => 'An ecommerce build and paid media for Forge & Field Fitness, a gym equipment brand.',
		],
		'sample-marlow-house-hotels' => [
			'year'         => '2025',
			'statement'    => 'Marlow House were paying commission on bookings their own website should have taken.',
			'support'      => 'The refresh puts rooms, dates and prices one tap from every page, with photography that sells the stay and a booking flow that keeps guests on the hotel\'s own site. Direct bookings now carry the weight the agencies used to.',
			'deliverables' => [ 'Booking-first UX', 'WordPress design and build', 'Photography direction', 'Rooms and rates system', 'Booking engine integration', 'Analytics and tracking' ],
			'captions'     => [
				'Every page keeps dates and rates one tap away.',
				'Room pages that sell the stay, not just list the furniture.',
				'A booking flow that keeps guests out of the agencies\' checkouts.',
			],
			'seo_desc'     => 'A booking-first website refresh for Marlow House Hotels, a boutique hotel group.',
		],
		'sample-northgate-audio' => [
			'year'         => '2024',
			'statement'    => 'Northgate Audio built a loyal shop-floor following; online, nobody could hear them.',
			'support'      => 'We put their hi-fi expertise to work across search and paid: campaigns built around the brands people already trust, buying guides that answer real questions and remarketing that brings researchers back when they are ready to spend.',
			'deliverables' => [ 'Digital marketing strategy', 'Google Ads management', 'Remarketing campaigns', 'Buying-guide content', 'Email campaigns', 'Monthly performance reviews' ],
			'captions'     => [
				'Campaigns organised by the brands buyers already search for.',
				'Buying guides that turn research visits into showroom appointments.',
				'One monthly view of spend, sales and what changes next.',
			],
			'seo_desc'     => 'Digital marketing and paid search for Northgate Audio, an independent hi-fi retailer.',
		],
		'sample-wrenfield-education' => [
			'year'         => '2024',
			'statement'    => 'Wrenfield\'s tutors change results; their website read like a printed prospectus.',
			'support'      => 'We rebuilt it around what parents actually weigh up: subjects, tutors, outcomes and price, answered plainly. New content covers every stage from entrance exams to A levels, and enquiries route straight to the right team.',
			'deliverables' => [ 'Website design and build', 'Content strategy', 'Subject and stage pages', 'Tutor profiles', 'Parent resource hub', 'Enquiry routing' ],
			'captions'     => [
				'Subject pages that answer what parents ask first.',
				'Tutor profiles that put the people front and centre.',
				'A resource hub that earns trust before the first enquiry.',
			],
			'seo_desc'     => 'A website and content programme for Wrenfield Education, a private tutoring group.',
		],
		'sample-calder-street-kitchen' => [
			'year'         => '2026',
			'statement'    => 'Calder Street Kitchen had the food and the queue; the brand told you none of it.',
			'support'      => 'We built the identity from the room outward: a wordmark with the confidence of the cooking, menus and signage that match the plates, and photography and social content that keep the tables full between word-of-mouth waves.',
			'deliverables' => [ 'Brand identity', 'Menu and signage design', 'Photography direction', 'Social content system', 'Launch campaign' ],
			'captions'     => [
				'The wordmark and palette, taken from the room itself.',
				'Menus designed to be read in low light and remembered after.',
				'A social content system the team can run from the pass.',
			],
			'seo_desc'     => 'Brand identity and content for Calder Street Kitchen, a neighbourhood restaurant.',
		],
		'sample-aldermere-events' => [
			'year'         => '2025',
			'statement'    => 'Aldermere fill venues for a living; their own funnel leaked at every stage.',
			'support'      => 'We rebuilt the marketing around each event\'s countdown: awareness early, urgency late and remarketing throughout. Ticket pages, tracking and paid campaigns now work as one system that fills rooms without discounting at the door.',
			'deliverables' => [ 'Campaign strategy', 'Paid search and social', 'Ticket page optimisation', 'Email countdown sequences', 'Conversion tracking', 'Post-event reporting' ],
			'captions'     => [
				'Campaigns phased around each event\'s countdown.',
				'Ticket pages tuned until the checkout stopped leaking.',
				'Remarketing that brings browsers back before the price rises.',
			],
			'seo_desc'     => 'Event marketing and paid campaigns for Aldermere Events.',
		],
	];
}

function vc_seed_work_run( $overwrite = false ) {
	// Field keys from acf-json/project.json and acf-json/project-details.json.
	$keys = [
		'sector'      => 'field_pj_0002',
		'description' => 'field_pj_0003',
		'year'        => 'field_pj_0101',
		'statement'   => 'field_pj_0102',
		'support'     => 'field_pj_0103',
		'deliv'       => 'field_pj_0104',
		'deliv_label' => 'field_pj_0105',
	];

	$fix_keys = [
		'pj_sector'      => $keys['sector'],
		'pj_description' => $keys['description'],
	];

	$report = [];
	foreach ( vc_seed_work_content() as $slug => $content ) {
		$post = get_page_by_path( $slug, OBJECT, 'project' );
		if ( ! $post ) {
			$report[ $slug ] = [ 'error' => 'Project not found — check the sample projects exist.' ];
			continue;
		}

		$post_id = $post->ID;
		$rows    = [];

		// Coherence fixes for known-wrong sample base data: always applied.
		foreach ( (array) ( $content['fix'] ?? [] ) as $meta_name => $value ) {
			if ( isset( $fix_keys[ $meta_name ] ) && get_post_meta( $post_id, $meta_name, true ) !== $value ) {
				update_field( $fix_keys[ $meta_name ], $value, $post_id );
				$rows[ $meta_name ] = 'fixed';
			}
		}

		$seed_simple = function ( $meta_name, $field_key, $value ) use ( $post_id, $overwrite, &$rows ) {
			$existing = get_post_meta( $post_id, $meta_name, true );
			if ( $existing && ! $overwrite ) {
				$rows[ $meta_name ] = 'kept existing';
				return;
			}
			update_field( $field_key, $value, $post_id );
			$rows[ $meta_name ] = $existing ? 'overwritten' : 'seeded';
		};

		$seed_simple( 'pj_year', $keys['year'], $content['year'] );
		$seed_simple( 'pj_overview_statement', $keys['statement'], $content['statement'] );
		$seed_simple( 'pj_overview_support', $keys['support'], $content['support'] );

		$existing_rows = (int) get_post_meta( $post_id, 'pj_deliverables', true );
		if ( $existing_rows > 0 && ! $overwrite ) {
			$rows['pj_deliverables'] = 'kept existing (' . $existing_rows . ' rows)';
		} else {
			$value = array_map( function ( $label ) use ( $keys ) {
				return [ $keys['deliv_label'] => $label ];
			}, $content['deliverables'] );
			update_field( $keys['deliv'], $value, $post_id );
			$rows['pj_deliverables'] = ( $existing_rows > 0 ? 'overwritten' : 'seeded' ) . ' (' . count( $content['deliverables'] ) . ' rows)';
		}

		// Yoast search description per project page.
		$existing_desc = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true );
		if ( $existing_desc && ! $overwrite ) {
			$rows['seo description'] = 'kept existing';
		} else {
			update_post_meta( $post_id, '_yoast_wpseo_metadesc', $content['seo_desc'] );
			$rows['seo description'] = $existing_desc ? 'overwritten' : 'seeded';
		}

		update_post_meta( $post_id, '_vc_sample_content', 1 );
		$report[ $slug ] = $rows;
	}

	return $report;
}

function vc_seed_work_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sorry, you are not allowed to access this page.' );
	}

	$report = null;
	if ( isset( $_POST['vc_seed_work'] ) ) {
		check_admin_referer( 'vc_seed_work' );
		$report = vc_seed_work_run( ! empty( $_POST['vc_seed_overwrite'] ) );
	}

	$projects = vc_seed_work_content();
	?>
	<div class="wrap">
		<h1>Seed Work</h1>
		<p>Fills the nine sample projects' showcase content in one click: year, overview statement and support, the deliverables list and the Yoast search description per project. Projects are matched by slug. Hero and gallery imagery is attached separately (media libraries differ per environment); the gallery captions live in this seeder as the canonical copy the image step reads.</p>
		<p><strong>By default nothing is overwritten:</strong> fields that already have content are left exactly as they are. The listed coherence fixes (sample base data that belonged to another sample) always apply.</p>

		<form method="post">
			<?php wp_nonce_field( 'vc_seed_work' ); ?>
			<label style="display:block;margin-bottom:12px;">
				<input type="checkbox" name="vc_seed_overwrite" value="1">
				Overwrite fields that already have content
			</label>
			<p class="submit">
				<button type="submit" class="button button-primary" name="vc_seed_work" value="1">Seed work content</button>
			</p>
		</form>

		<?php if ( null !== $report ) : ?>
			<h2>Results</h2>
			<?php foreach ( $report as $slug => $rows ) : ?>
				<h3><code><?php echo esc_html( $slug ); ?></code></h3>
				<?php if ( isset( $rows['error'] ) ) : ?>
					<p style="color:#b32d2e;"><?php echo esc_html( $rows['error'] ); ?></p>
				<?php else : ?>
					<table class="widefat striped" style="max-width:640px;margin-bottom:16px;">
						<tbody>
							<?php foreach ( $rows as $field => $result ) : ?>
								<tr>
									<td><code><?php echo esc_html( $field ); ?></code></td>
									<td><?php echo esc_html( $result ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<h2>What will be seeded</h2>
		<ul style="list-style:disc;padding-left:20px;">
			<?php foreach ( $projects as $slug => $content ) : ?>
				<li><code><?php echo esc_html( $slug ); ?></code>: year, overview, <?php echo count( $content['deliverables'] ); ?> deliverables, SEO description<?php echo ! empty( $content['fix'] ) ? ', ' . count( $content['fix'] ) . ' coherence fixes' : ''; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
