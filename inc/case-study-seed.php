<?php

/**
 * Settings > Seed Case Studies: one-click seeding of the per-case-study
 * narrative content (year, overview, challenge, approach, results stats and
 * narrative, the linked testimonial, service terms and the Yoast search
 * description), the shared Case Studies Page options copy, and the matching
 * sample project per case study (created by slug if missing, fields filled
 * and pj_case_study linked) so the work-to-case-study links resolve on real
 * entries. Everything is matched by SLUG, never by ID, so it works on staging
 * and production where post IDs differ. By default only empty fields are
 * filled; existing edits are never touched unless "overwrite" is ticked.
 * Hero, gallery and testimonial imagery is attached separately (media
 * libraries differ per environment).
 */

add_action( 'admin_menu', 'vc_seed_case_studies_menu' );
function vc_seed_case_studies_menu() {
	add_options_page(
		'Seed Case Studies',
		'Seed Case Studies',
		'manage_options',
		'vc-seed-case-studies',
		'vc_seed_case_studies_page'
	);
}

/**
 * The launch content, keyed by case study slug. All of it is [SAMPLE]
 * fictional client copy following the site style guide; field keys belong to
 * the "Case Study Details" ACF group (acf-json/case-study-details.json) and,
 * for the nested project blocks, the "Project" and "Project Details" groups.
 */
function vc_seed_case_studies_content() {
	return [
		'sample-northbridge-property-group' => [
			'year'                => '2025',
			'overview_statement'  => 'Northbridge sell new-build family homes across the North West, and their enquiries had stalled while their developments had not.',
			'overview_support'    => 'A regional developer with a strong pipeline and a dated shop window: a brand that undersold the homes and a website that made interested buyers work for every answer. We rebuilt both, then measured everything.',
			'challenge_statement' => 'Plenty of site visits, hardly any serious buyers at the end of them.',
			'challenge_body'      => "Northbridge's marketing generated traffic, but the website gave buyers nowhere to go. Development pages buried prices and timelines, floor plans lived in PDF downloads, and the only route to the sales team was a generic contact form that asked less than a viewing appointment needs. Enquiries arrived thin: no budget, no development, no timeframe.\n\nThe sales team paid for it twice. They spent hours qualifying leads the website should have qualified for them, and genuine buyers, the ones comparing three developers in an afternoon, moved on to whichever site answered first. The brand did not help; it read as smaller than the business had become.",
			'approach_body'       => "We started with the questions buyers actually ask: where are the homes, what do they cost, when can we move in, and can we trust the people building them? The new identity answered the last one, confident and consistent from hoardings to homepage. The new WordPress site answered the rest: every development leads with plans, prices and timelines, and every page ends with a clear next step.\n\nThe enquiry journey did the qualifying. Short forms ask for the development, budget and timeframe up front, so the sales team open each enquiry knowing whether it is a viewing, a callback or a brochure request. Behind it, tracking ties every enquiry to its source, so spend follows what works.\n\nWe launched development by development rather than in one leap, which kept lead flow running and let us tune the journey on live traffic.",
			'stats'               => [
				[ '2.4x', 'more qualified leads in six months' ],
				[ '57%', 'fewer unqualified enquiries reaching the sales team' ],
				[ '41%', 'more time spent on development pages' ],
			],
			'results_narrative'   => 'Measured over the six months after launch against the six before it, with the same media spend. Qualified means a named development, a budget and a timeframe on the enquiry. The sales team now spend their time on viewings rather than triage.',
			'seo_desc'            => 'How a brand and website rebuild took Northbridge Property Group to 2.4x qualified leads in six months.',
			'services'            => [ 'branding', 'web-design-development' ],
			'testimonial_company' => 'Northbridge Property Group',
			'project'             => [
				'slug'         => 'sample-northbridge-property-group',
				'title'        => '[SAMPLE] Northbridge Property Group',
				'client'       => 'Northbridge Property Group',
				'sector'       => 'Property',
				'description'  => 'A brand and website rebuild for a property developer, built to answer buyers\' questions before the sales team has to.',
				'year'         => '2025',
				'statement'    => 'Northbridge sell family homes off plan across the North West; the brand and the website had to earn buyer trust before the first viewing.',
				'support'      => 'A new identity with the confidence of the developments themselves, and a WordPress build that leads every page with plans, prices and timelines. Short, structured enquiry forms qualify buyers as they arrive, and tracking ties every enquiry to its source. The full story, with the numbers, is in the case study.',
				'deliverables' => [ 'Brand identity', 'WordPress design and build', 'Development listings system', 'Enquiry journey design', 'Analytics and enquiry tracking' ],
				'seo_desc'     => 'Brand identity and a conversion-first website rebuild for Northbridge Property Group, a North West property developer.',
			],
		],
		'sample-halcyon-events' => [
			'year'                => '2024-25',
			'overview_statement'  => 'Halcyon plan corporate events across London and the South East, in a market where every enquiry starts with a search.',
			'overview_support'    => 'Award-winning delivery, word-of-mouth growth and a paid search account that had quietly become their most expensive department. We rebuilt it around how event budgets actually move through the year.',
			'challenge_statement' => 'The clicks kept getting dearer and the enquiries kept getting thinner.',
			'challenge_body'      => "Halcyon's Google Ads account had grown by accretion: years of overlapping campaigns, broad keywords bidding against each other and every click landing on the homepage. In an auction full of venues, caterers and rival planners, they paid specialist prices for generalist traffic.\n\nThe deeper problem was timing. Corporate event demand is seasonal, with Christmas parties booked from September and summer events from March, but the account spent evenly through the year. Budget ran out when intent peaked and burned when nobody was buying.",
			'approach_body'       => "We rebuilt the account from search terms up: one campaign per event type, exact and phrase match around the searches that mention a budget, a headcount or a date, and negatives that keep the generic clicks out. Every ad now lands on a dedicated page for that event type, with venues, sample budgets and a short enquiry form above the fold.\n\nThen we matched the spend to the calendar. Budgets phase up ahead of each booking season and pull back out of it, and creative refreshes every quarter so the ads sell Christmas parties in September and summer parties in March, not a generic events service all year round.\n\nRemarketing catches the researchers: anyone who visited a landing page without enquiring sees the same event type again when they come back to search.",
			'stats'               => [
				[ '68%', 'lower cost per enquiry year on year' ],
				[ '2.9x', 'more enquiries from a smaller annual budget' ],
				[ '2.2x', 'higher landing page conversion rate' ],
			],
			'results_narrative'   => 'Compared like for like across two full years, so both sides include a Christmas season. The account now books enquiries at a cost the margins can carry, and the quarterly creative cycle keeps performance from decaying between seasons.',
			'seo_desc'            => 'How restructured paid search and seasonal landing pages cut Halcyon Events\' cost per enquiry by 68% year on year.',
			'services'            => [ 'paid-search-ppc', 'digital-marketing' ],
			'testimonial_company' => 'Halcyon Events',
			'project'             => [
				'slug'         => 'sample-halcyon-events',
				'title'        => '[SAMPLE] Halcyon Events',
				'client'       => 'Halcyon Events',
				'sector'       => 'Events',
				'description'  => 'Paid search rebuilt around the events calendar, with a dedicated landing page for every event type.',
				'year'         => '2024-25',
				'statement'    => 'Halcyon plan corporate events for a living; their paid search had to stop paying generalist prices for specialist enquiries.',
				'support'      => 'One campaign per event type, dedicated landing pages with venues and sample budgets above the fold, and spend phased around the booking seasons rather than spread across the year. Creative refreshes every quarter so the ads always sell the season people are buying. The numbers behind it are in the case study.',
				'deliverables' => [ 'Paid search restructure', 'Seasonal budget phasing', 'Event landing pages', 'Quarterly creative refresh', 'Remarketing', 'Conversion tracking' ],
				'seo_desc'     => 'Paid search and seasonal landing pages for Halcyon Events, a corporate event planner.',
			],
		],
		'sample-fenwick-frost' => [
			'year'                => '2024',
			'overview_statement'  => 'Fenwick & Frost sell considered homeware to people who research before they buy, and organic search barely knew they existed.',
			'overview_support'    => 'A well-loved independent brand with retention most stores would envy, hidden behind a site that ranked for its own name and little else. We built the search presence their products deserved and removed the friction between finding and buying.',
			'challenge_statement' => 'New customers arrived through paid ads or not at all.',
			'challenge_body'      => "Almost every new customer was bought. Paid social drove launches, brand search caught the loyalists, and the middle, the thousands of monthly searches for the things Fenwick & Frost actually sell, went to marketplaces and bigger rivals. Category pages were thin, product descriptions were duplicated from supplier feeds, and blog posts read nicely but targeted nothing.\n\nThe site made it worse at the last step. Slow templates and a five-step checkout leaked the traffic that did arrive, so even the rankings they held converted below the category norm.",
			'approach_body'       => "We started where the demand was: a keyword map of every buying search in their range, matched page by page against the site. Category pages were rebuilt as landing pages, with real copy, buying advice and internal links that pass authority down to products. Descriptions were rewritten from the supplier feed up, one product at a time, prioritised by search volume and margin.\n\nThe content programme filled the research gap: buying guides and comparison pieces for the searches people run two weeks before they spend. Each one exists to rank, answer and hand over to the right category.\n\nTechnical work ran alongside: template speed, structured data for products and reviews, and a checkout cut from five steps to two, so the new traffic landed on a site that converts.",
			'stats'               => [
				[ '3.1x', 'organic revenue growth in twelve months' ],
				[ '2.9x', 'more organic sessions to buying pages' ],
				[ '74', 'buying keywords in the top three, up from nine' ],
			],
			'results_narrative'   => 'Twelve months from kickoff against the twelve before it, with paid spend held flat, so the growth is organic in both senses. Rankings, sessions and revenue are reported together each month; the revenue line is the one that pays for the programme.',
			'seo_desc'            => 'How an SEO and content programme grew Fenwick & Frost\'s organic revenue 3.1x in twelve months.',
			'services'            => [ 'seo-ai-search', 'content-creation' ],
			'testimonial_company' => 'Fenwick & Frost',
			'project'             => [
				'slug'         => 'sample-fenwick-frost',
				'title'        => '[SAMPLE] Fenwick & Frost',
				'client'       => 'Fenwick & Frost',
				'sector'       => 'Ecommerce',
				'description'  => 'An SEO and content programme for an independent homeware store, from keyword map to checkout.',
				'year'         => '2024',
				'statement'    => 'Fenwick & Frost\'s products deserved to be found; the site gave search engines almost nothing to rank.',
				'support'      => 'Category pages rebuilt as landing pages, product copy rewritten from the supplier feed up, and buying guides that catch researchers two weeks before they spend. Technical fixes and a two-step checkout make sure the traffic converts once it lands. The results are in the case study.',
				'deliverables' => [ 'Keyword and content mapping', 'Category page rebuild', 'Product copy rewrite', 'Buying guides', 'Technical SEO and site speed', 'Structured data' ],
				'seo_desc'     => 'An SEO and content programme for Fenwick & Frost, an independent homeware retailer.',
			],
		],
	];
}

/**
 * The Case Studies Page options copy (the archive hero, filter label and the
 * shared single-page section headings), seeded so the edit screen shows real
 * content rather than relying on ACF defaults alone. Keyed by field key;
 * names are listed for the report only.
 */
function vc_seed_case_studies_options() {
	return [
		'field_csp_0001' => [ 'csp_hero_heading_start', 'The' ],
		'field_csp_0002' => [ 'csp_hero_heading_red', 'results' ],
		'field_csp_0003' => [ 'csp_hero_heading_end', 'behind the work.' ],
		'field_csp_0004' => [ 'csp_hero_subheading', 'Each study follows one project from challenge to measured result: what the client needed, what we built, and the numbers it delivered.' ],
		'field_csp_0005' => [ 'csp_filter_all_label', 'All Case Studies' ],
		'field_csp_0006' => [ 'csp_overview_heading_start', 'At a' ],
		'field_csp_0007' => [ 'csp_overview_heading_red', 'glance' ],
		'field_csp_0008' => [ 'csp_overview_heading_end', '.' ],
		'field_csp_0009' => [ 'csp_challenge_heading_start', 'The' ],
		'field_csp_0010' => [ 'csp_challenge_heading_red', 'challenge' ],
		'field_csp_0011' => [ 'csp_challenge_heading_end', '.' ],
		'field_csp_0012' => [ 'csp_approach_heading_start', 'The' ],
		'field_csp_0013' => [ 'csp_approach_heading_red', 'approach' ],
		'field_csp_0014' => [ 'csp_approach_heading_end', '.' ],
		'field_csp_0015' => [ 'csp_results_heading_start', 'The' ],
		'field_csp_0016' => [ 'csp_results_heading_red', 'results' ],
		'field_csp_0017' => [ 'csp_results_heading_end', '.' ],
		'field_csp_0018' => [ 'csp_testimonial_heading_start', 'In their' ],
		'field_csp_0019' => [ 'csp_testimonial_heading_red', 'words' ],
		'field_csp_0020' => [ 'csp_testimonial_heading_end', '.' ],
		'field_csp_0021' => [ 'csp_related_heading_start', 'The' ],
		'field_csp_0022' => [ 'csp_related_heading_red', 'work' ],
		'field_csp_0023' => [ 'csp_related_heading_end', 'behind it.' ],
		'field_csp_0024' => [ 'csp_cta_heading_start', 'Want a result like' ],
		'field_csp_0025' => [ 'csp_cta_heading_red', 'this' ],
		'field_csp_0026' => [ 'csp_cta_heading_end', '?' ],
		'field_csp_0027' => [ 'csp_cta_subheading', 'Tell us the result you are after and we will reply within one working day with a clear next step.' ],
	];
}

function vc_seed_case_studies_run( $overwrite = false ) {
	// Field keys from acf-json/case-study-details.json, project.json and
	// project-details.json.
	$cs_keys = [
		'year'                => 'field_cs_0101',
		'overview_statement'  => 'field_cs_0102',
		'overview_support'    => 'field_cs_0103',
		'challenge_statement' => 'field_cs_0104',
		'challenge_body'      => 'field_cs_0105',
		'approach_body'       => 'field_cs_0106',
		'results_narrative'   => 'field_cs_0113',
	];
	$cs_meta = [
		'year'                => 'cs_year',
		'overview_statement'  => 'cs_overview_statement',
		'overview_support'    => 'cs_overview_support',
		'challenge_statement' => 'cs_challenge_statement',
		'challenge_body'      => 'cs_challenge_body',
		'approach_body'       => 'cs_approach_body',
		'results_narrative'   => 'cs_results_narrative',
	];
	$pj_keys = [
		'client'      => 'field_pj_0001',
		'sector'      => 'field_pj_0002',
		'description' => 'field_pj_0003',
		'year'        => 'field_pj_0101',
		'statement'   => 'field_pj_0102',
		'support'     => 'field_pj_0103',
		'deliv'       => 'field_pj_0104',
		'deliv_label' => 'field_pj_0105',
		'case_study'  => 'field_pj_0109',
	];

	$report = [];
	foreach ( vc_seed_case_studies_content() as $slug => $content ) {
		$post = get_page_by_path( $slug, OBJECT, 'case_study' );
		if ( ! $post ) {
			$report[ $slug ] = [ 'error' => 'Case study not found: check the sample case studies exist.' ];
			continue;
		}

		$cs_id = $post->ID;
		$rows  = [];

		$seed_simple = function ( $meta_name, $field_key, $value, $post_id ) use ( $overwrite, &$rows ) {
			$existing = get_post_meta( $post_id, $meta_name, true );
			if ( $existing && ! $overwrite ) {
				$rows[ $meta_name ] = 'kept existing';
				return;
			}
			update_field( $field_key, $value, $post_id );
			$rows[ $meta_name ] = $existing ? 'overwritten' : 'seeded';
		};

		foreach ( $cs_keys as $part => $field_key ) {
			$seed_simple( $cs_meta[ $part ], $field_key, $content[ $part ], $cs_id );
		}

		// Results stats repeater.
		$existing_rows = (int) get_post_meta( $cs_id, 'cs_results_stats', true );
		if ( $existing_rows > 0 && ! $overwrite ) {
			$rows['cs_results_stats'] = 'kept existing (' . $existing_rows . ' rows)';
		} else {
			$value = array_map( function ( $stat ) {
				return [
					'field_cs_0111' => $stat[0],
					'field_cs_0112' => $stat[1],
				];
			}, $content['stats'] );
			update_field( 'field_cs_0110', $value, $cs_id );
			$rows['cs_results_stats'] = ( $existing_rows > 0 ? 'overwritten' : 'seeded' ) . ' (' . count( $content['stats'] ) . ' rows)';
		}

		// The client's testimonial, matched by company name so the link works
		// wherever the testimonial post IDs differ.
		$existing_tm = get_post_meta( $cs_id, 'cs_testimonial', true );
		if ( $existing_tm && ! $overwrite ) {
			$rows['cs_testimonial'] = 'kept existing';
		} else {
			$tm_match = null;
			foreach ( get_posts( [ 'post_type' => 'testimonial', 'numberposts' => -1 ] ) as $tm ) {
				if ( get_field( 'tm_company', $tm->ID ) === $content['testimonial_company'] ) {
					$tm_match = $tm->ID;
					break;
				}
			}
			if ( $tm_match ) {
				update_field( 'field_cs_0114', $tm_match, $cs_id );
				$rows['cs_testimonial'] = ( $existing_tm ? 'overwritten' : 'seeded' ) . ' (post ' . $tm_match . ')';
			} else {
				$rows['cs_testimonial'] = 'no testimonial found for "' . $content['testimonial_company'] . '"';
			}
		}

		// Service terms.
		$existing_terms = wp_get_post_terms( $cs_id, 'service', [ 'fields' => 'ids' ] );
		if ( ! is_wp_error( $existing_terms ) && count( $existing_terms ) > 0 && ! $overwrite ) {
			$rows['services'] = 'kept existing (' . count( $existing_terms ) . ' terms)';
		} else {
			wp_set_object_terms( $cs_id, $content['services'], 'service' );
			$rows['services'] = 'assigned (' . implode( ', ', $content['services'] ) . ')';
		}

		// Yoast search description per case study page.
		$existing_desc = get_post_meta( $cs_id, '_yoast_wpseo_metadesc', true );
		if ( $existing_desc && ! $overwrite ) {
			$rows['seo description'] = 'kept existing';
		} else {
			update_post_meta( $cs_id, '_yoast_wpseo_metadesc', $content['seo_desc'] );
			$rows['seo description'] = $existing_desc ? 'overwritten' : 'seeded';
		}

		update_post_meta( $cs_id, '_vc_sample_content', 1 );

		// The matching sample project: created by slug if missing, fields
		// filled the same non-destructive way, and pj_case_study linked so
		// the project-to-case-study cross-link resolves both ways.
		$pj_content = $content['project'];
		$project    = get_page_by_path( $pj_content['slug'], OBJECT, 'project' );
		if ( ! $project ) {
			$pj_id = wp_insert_post( [
				'post_type'   => 'project',
				'post_status' => 'publish',
				'post_title'  => $pj_content['title'],
				'post_name'   => $pj_content['slug'],
			] );
			if ( is_wp_error( $pj_id ) || ! $pj_id ) {
				$rows['project'] = 'creation failed';
				$report[ $slug ] = $rows;
				continue;
			}
			$rows['project'] = 'created (post ' . $pj_id . ')';
		} else {
			$pj_id           = $project->ID;
			$rows['project'] = 'exists (post ' . $pj_id . ')';
		}

		$seed_simple( 'pj_client_name', $pj_keys['client'], $pj_content['client'], $pj_id );
		$seed_simple( 'pj_sector', $pj_keys['sector'], $pj_content['sector'], $pj_id );
		$seed_simple( 'pj_description', $pj_keys['description'], $pj_content['description'], $pj_id );
		$seed_simple( 'pj_year', $pj_keys['year'], $pj_content['year'], $pj_id );
		$seed_simple( 'pj_overview_statement', $pj_keys['statement'], $pj_content['statement'], $pj_id );
		$seed_simple( 'pj_overview_support', $pj_keys['support'], $pj_content['support'], $pj_id );

		$existing_deliv = (int) get_post_meta( $pj_id, 'pj_deliverables', true );
		if ( $existing_deliv > 0 && ! $overwrite ) {
			$rows['pj_deliverables'] = 'kept existing (' . $existing_deliv . ' rows)';
		} else {
			$value = array_map( function ( $label ) use ( $pj_keys ) {
				return [ $pj_keys['deliv_label'] => $label ];
			}, $pj_content['deliverables'] );
			update_field( $pj_keys['deliv'], $value, $pj_id );
			$rows['pj_deliverables'] = ( $existing_deliv > 0 ? 'overwritten' : 'seeded' ) . ' (' . count( $pj_content['deliverables'] ) . ' rows)';
		}

		$existing_link = get_post_meta( $pj_id, 'pj_case_study', true );
		if ( $existing_link && ! $overwrite ) {
			$rows['pj_case_study'] = 'kept existing';
		} else {
			update_field( $pj_keys['case_study'], $cs_id, $pj_id );
			$rows['pj_case_study'] = 'linked (case study ' . $cs_id . ')';
		}

		$existing_pj_terms = wp_get_post_terms( $pj_id, 'service', [ 'fields' => 'ids' ] );
		if ( ! is_wp_error( $existing_pj_terms ) && count( $existing_pj_terms ) > 0 && ! $overwrite ) {
			$rows['project services'] = 'kept existing (' . count( $existing_pj_terms ) . ' terms)';
		} else {
			wp_set_object_terms( $pj_id, $content['services'], 'service' );
			$rows['project services'] = 'assigned (' . implode( ', ', $content['services'] ) . ')';
		}

		$existing_pj_desc = get_post_meta( $pj_id, '_yoast_wpseo_metadesc', true );
		if ( $existing_pj_desc && ! $overwrite ) {
			$rows['project seo description'] = 'kept existing';
		} else {
			update_post_meta( $pj_id, '_yoast_wpseo_metadesc', $pj_content['seo_desc'] );
			$rows['project seo description'] = $existing_pj_desc ? 'overwritten' : 'seeded';
		}

		update_post_meta( $pj_id, '_vc_sample_content', 1 );

		$report[ $slug ] = $rows;
	}

	// The shared Case Studies Page options copy (editor-first rule: saved
	// values, not defaults alone).
	$option_rows = [];
	foreach ( vc_seed_case_studies_options() as $field_key => $pair ) {
		list( $name, $value ) = $pair;
		$existing = get_field( $name, 'option' );
		if ( $existing && ! $overwrite ) {
			$option_rows[ $name ] = 'kept existing';
			continue;
		}
		update_field( $field_key, $value, 'option' );
		$option_rows[ $name ] = $existing ? 'overwritten' : 'seeded';
	}
	$report['case-studies-page-options'] = $option_rows;

	return $report;
}

function vc_seed_case_studies_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sorry, you are not allowed to access this page.' );
	}

	$report = null;
	if ( isset( $_POST['vc_seed_case_studies'] ) ) {
		check_admin_referer( 'vc_seed_case_studies' );
		$report = vc_seed_case_studies_run( ! empty( $_POST['vc_seed_overwrite'] ) );
	}

	$studies = vc_seed_case_studies_content();
	?>
	<div class="wrap">
		<h1>Seed Case Studies</h1>
		<p>Fills the three sample case studies' narrative content in one click: year, overview, challenge, approach, results stats and narrative, the linked client testimonial, service terms and the Yoast search description. Creates the matching sample project for each case study if it does not exist, fills its fields and links it back through the project's Case Study field. Also seeds the shared Case Studies Page options copy. Everything is matched by slug. Hero and gallery imagery is attached separately (media libraries differ per environment).</p>
		<p><strong>By default nothing is overwritten:</strong> fields that already have content are left exactly as they are.</p>

		<form method="post">
			<?php wp_nonce_field( 'vc_seed_case_studies' ); ?>
			<label style="display:block;margin-bottom:12px;">
				<input type="checkbox" name="vc_seed_overwrite" value="1">
				Overwrite fields that already have content
			</label>
			<p class="submit">
				<button type="submit" class="button button-primary" name="vc_seed_case_studies" value="1">Seed case study content</button>
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
			<?php foreach ( $studies as $slug => $content ) : ?>
				<li><code><?php echo esc_html( $slug ); ?></code>: narrative content, <?php echo count( $content['stats'] ); ?> stats, testimonial link, <?php echo count( $content['services'] ); ?> service terms, SEO description, plus the matching project <code><?php echo esc_html( $content['project']['slug'] ); ?></code></li>
			<?php endforeach; ?>
			<li><code>case-studies-page-options</code>: the archive hero, filter label and shared section headings</li>
		</ul>
	</div>
	<?php
}
