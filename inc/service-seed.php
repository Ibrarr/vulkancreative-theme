<?php

/**
 * Settings > Seed Services: one-click seeding of the per-term service page
 * content (intro, deliverables, process steps, Yoast descriptions and the
 * sample stats) so a fresh environment does not need every field typed in by
 * hand. Terms are matched by SLUG, never by ID, so it works on staging and
 * production where term IDs differ. By default only empty fields are filled —
 * existing edits are never touched unless "overwrite" is ticked. Everything
 * that is not seeded here (headings, hub copy) already ships as ACF defaults
 * and template fallbacks.
 */

add_action( 'admin_menu', 'vc_seed_services_menu' );
function vc_seed_services_menu() {
	add_options_page(
		'Seed Services',
		'Seed Services',
		'manage_options',
		'vc-seed-services',
		'vc_seed_services_page'
	);
}

/**
 * The launch content, keyed by term slug. This is the canonical seed source:
 * the same copy reviewed on the local build (deliverables in their trimmed
 * ~20-30 word form). Field keys belong to the "Service Page" ACF group
 * (acf-json/service-page.json).
 */
function vc_seed_services_content() {
	return [
		'web-design-development' => [
			'statement'    => 'A website that earns its keep.',
			'support'      => 'For businesses whose site has real work to do: explain the offer clearly, rank well, load fast and turn visits into enquiries. Designed and built in-house, so nothing gets lost between a design agency and a dev shop.',
			'deliverables' => [
				[ 'Discovery and UX strategy', 'Every build starts with research, not guesswork. We map your audience, their questions and the shortest path to an enquiry, then you sign off the plan before any design begins.' ],
				[ 'Bespoke design', 'No themes and no templates: a design system drawn around your brand, your buyers and your goals, reviewed with you screen by screen before development starts.' ],
				[ 'WordPress development', 'Hand-built WordPress with clean, documented code you own outright. Fast, accessible and easy to edit, so content changes never need a developer on standby.' ],
				[ 'Conversion-ready pages', 'Pages engineered to turn interest into enquiries: clear messaging, calls to action where attention lands and forms that take seconds, with every step measured.' ],
				[ 'SEO foundations', 'Technical SEO built in from day one: clean structure, fast pages, structured data and analytics wired before launch, so your site starts life visible to search and AI.' ],
				[ 'Support that stays', 'Launch is the start, not the finish: training, documentation and a direct line to the team who built your site, with updates that keep it fast and secure.' ],
			],
			'steps'        => [
				[ 'Discover', 'We audit your current site, analytics and competitors, and agree what success looks like.' ],
				[ 'Design', 'Wireframes to full design, reviewed with you at every step. You see it before we build it.' ],
				[ 'Build', 'Development, content entry and testing across devices, browsers and screen readers.' ],
				[ 'Launch and improve', 'A managed go-live, then measurement and refinements once real visitors arrive.' ],
			],
			// Real figures still pending: [SAMPLE]-flagged per the site convention.
			'stats'        => [
				[ '2.3x', '[SAMPLE] Average lead growth after relaunch' ],
				[ '150%', '[SAMPLE] More organic enquiries in six months' ],
				[ '0.9s', '[SAMPLE] Average page load after rebuild' ],
			],
			'seo_desc'     => 'Bespoke WordPress web design and development. Fast, accessible, conversion-ready websites built in-house by Vulkan Creative.',
		],
		'seo-ai-search' => [
			'statement'    => 'Found where your buyers actually look.',
			'support'      => 'Search is no longer ten blue links. We optimise for traditional rankings and the AI answers that increasingly sit above them, so qualified buyers find you first in both.',
			'deliverables' => [
				[ 'Technical SEO audit and fixes', 'A full crawl to find what holds rankings back: speed, indexing and structure, ranked by impact. We fix what we find and prove it with before-and-after data.' ],
				[ 'Keyword and intent strategy', 'Research into what your buyers actually type and what they mean by it: a keyword map tied to pages and business value, targeting searches that become enquiries.' ],
				[ 'On-page optimisation', 'Titles, headings, internal links and copy tuned page by page, each targeting one clear intent, so your pages reinforce each other instead of competing.' ],
				[ 'AI search optimisation', "Content structured so ChatGPT, Gemini and Google's AI Overviews can read, trust and cite you, putting your brand inside the AI answers buyers read first." ],
				[ 'Authority building', 'Digital PR and link earning from relevant, credible publications, raising your whole domain so every mention compounds your visibility and reputation together.' ],
				[ 'Reporting that means something', 'One monthly view of rankings, traffic and enquiries: what moved, why and what we do next, in plain English you can forward to the board.' ],
			],
			'steps'        => [
				[ 'Audit', 'We benchmark your visibility, technical health and competitors.' ],
				[ 'Prioritise', 'The fixes and content that will move enquiries soonest come first.' ],
				[ 'Implement', 'We make the changes and publish the content, working in your CMS.' ],
				[ 'Compound', 'Monthly cycles of measurement and refinement, so gains stack.' ],
			],
			'seo_desc'     => 'SEO and AI search optimisation that wins visibility in rankings and AI answers. Technical fixes, content and authority from Vulkan Creative.',
		],
		'digital-marketing' => [
			'statement'    => 'One strategy, every channel pulling together.',
			'support'      => 'For teams tired of channels run in silos. We plan search, paid, email and content as one system, measure what each contributes and move budget to what works.',
			'deliverables' => [
				[ 'Marketing strategy', 'A plan built backwards from revenue: who to reach, where they spend attention and which channels deserve budget, so every campaign answers to a number.' ],
				[ 'Channel management', 'Search, paid, email and social run day to day by one team, so budget moves to what performs and campaigns reinforce each other.' ],
				[ 'Analytics and tracking', 'Clean measurement you can trust: tracking that fires correctly, attribution that reflects reality and dashboards you can read in a minute.' ],
				[ 'Landing pages and funnels', 'Journeys built to convert the traffic your channels win: focused landing pages, logical next steps and forms without friction.' ],
				[ 'Testing programme', 'A steady rhythm of experiments across ads, pages and messaging: winners scaled, losers cut quickly and every result recorded so performance compounds.' ],
				[ 'Monthly performance reviews', 'A working session, not a slide deck: what performed, what did not and what changes next month, so you always know what the budget brought back.' ],
			],
			'steps'        => [
				[ 'Audit', 'We review every live channel, your data and your funnel.' ],
				[ 'Plan', 'Budgets, channels and targets agreed in one joined-up roadmap.' ],
				[ 'Launch', 'Campaigns live with tracking proven before a pound is spent.' ],
				[ 'Optimise', 'Weekly tuning and monthly reviews move budget to what performs.' ],
			],
			'seo_desc'     => 'Joined-up digital marketing across search, paid, email and content. One accountable team and clear reporting from Vulkan Creative.',
		],
		'paid-search-ppc' => [
			'statement'    => 'Demand captured at the exact moment it exists.',
			'support'      => 'Google and Microsoft ads built around search intent: tight keyword sets, ads that match the query and landing pages that finish the job. Lower cost per enquiry, not just more clicks.',
			'deliverables' => [
				[ 'Account architecture', 'Campaigns and ad groups structured for control: tight themes, clean data and budget that flows to where the returns are.' ],
				[ 'Keyword and match strategy', 'Spend focused on searches with buying intent, defended by a ruthless negative keyword list, so budget stops leaking on lookalike queries.' ],
				[ 'Ad copy and testing', 'Ads written to match the query and tested continuously: stronger ads, better quality scores, cheaper clicks and more of the right visitors.' ],
				[ 'Landing page alignment', 'Every ad lands on a page that finishes the sentence it started, lifting quality scores and conversion rates together.' ],
				[ 'Bid and budget management', 'Daily control of bids, budgets and placements, with automated rules watched by humans, so spend flexes towards what converts.' ],
				[ 'Transparent reporting', 'Cost per enquiry front and centre, with spend, clicks and conversions behind it: exactly what each pound bought, every month.' ],
			],
			'steps'        => [
				[ 'Audit', 'Existing accounts reviewed for waste, gaps and quick wins.' ],
				[ 'Build', 'Campaigns, ads and tracking constructed properly from the start.' ],
				[ 'Launch', 'A controlled go-live with conversion tracking proven.' ],
				[ 'Optimise', 'Continuous query mining, bid tuning and creative testing.' ],
			],
			'seo_desc'     => 'Paid search and PPC management that lowers cost per enquiry. Google and Microsoft ads run in-house by Vulkan Creative.',
		],
		'content-creation' => [
			'statement'    => 'Content that answers, ranks and persuades.',
			'support'      => 'Web pages, articles, guides and case studies mapped to the questions your buyers actually ask, written to be cited by search engines and AI assistants alike.',
			'deliverables' => [
				[ 'Content strategy', 'Topics chosen where search demand and sales value overlap, prioritised by what will move enquiries soonest, in a pipeline your team can sustain.' ],
				[ 'Web and landing page copy', 'Copy that explains clearly and sells without the hard sell, written for people first and structured for search engines and AI assistants close behind.' ],
				[ 'Articles and guides', 'Genuinely useful long-form pieces that answer real questions, earn rankings and get cited by AI search, written to be the best answer available.' ],
				[ 'Case studies', 'Your results told as stories buyers believe: the situation, what we did and the numbers that followed, closing deals when your team cannot be in the room.' ],
				[ 'Content refreshes', 'Existing pages audited and updated so slipping rankings recover and strong ones climb: often the fastest content win available.' ],
				[ 'Editorial calendar', 'A realistic publishing rhythm mapped months ahead: what publishes, when and targeting which search, because consistency beats bursts.' ],
			],
			'steps'        => [
				[ 'Map', 'We map audience questions to the content that should answer them.' ],
				[ 'Brief', 'Every piece gets a purpose, keyword and structure before writing.' ],
				[ 'Create', 'Written, designed and reviewed with you before anything ships.' ],
				[ 'Measure', 'Rankings, reads and enquiries per piece, refreshed on a cycle.' ],
			],
			'seo_desc'     => 'Content creation mapped to the questions your buyers ask: web copy, articles, guides and case studies from Vulkan Creative.',
		],
		'branding' => [
			'statement'    => 'A brand your buyers remember and trust.',
			'support'      => 'Positioning, messaging and visual identity built as one system. Strong foundations that make every website, campaign and post work harder.',
			'deliverables' => [
				[ 'Brand strategy and positioning', 'Where you sit, who you serve and why you win, agreed in plain words first, because positioning is the decision every other brand choice inherits.' ],
				[ 'Messaging framework', 'Your story, value propositions and tone of voice codified, so sales, marketing and the newest hire all tell the same story.' ],
				[ 'Visual identity', 'Logo, colour, typography and image style designed as one flexible system, built to hold up everywhere from the website to the side of a van.' ],
				[ 'Brand guidelines', 'A practical playbook that keeps the brand sharp without policing it, so your team and your suppliers stay on brand without asking twice.' ],
				[ 'Collateral design', 'Decks, documents, templates and social assets that look like you at your best, doing quiet sales work at every touchpoint.' ],
				[ 'Rollout support', 'The new brand applied in the right order across your website, socials and materials, landing everywhere at once rather than half-finished.' ],
			],
			'steps'        => [
				[ 'Immerse', 'Workshops with your team, plus customer and competitor research.' ],
				[ 'Position', 'Strategy and messaging agreed before any visual work starts.' ],
				[ 'Design', 'Identity concepts, refined with you into one confident system.' ],
				[ 'Launch', 'Guidelines, assets and rollout across every touchpoint.' ],
			],
			'seo_desc'     => 'Brand strategy, messaging and visual identity built as one system. Branding that lifts every campaign, from Vulkan Creative.',
		],
	];
}

/**
 * Run the seed. Fields with existing content are kept unless $overwrite;
 * writes go through update_field() by field key so ACF stores the repeater
 * meta correctly. Returns a per-term report for the results table.
 */
function vc_seed_services_run( $overwrite = false ) {
	// Field keys from the "Service Page" group (acf-json/service-page.json).
	$keys = [
		'statement'  => 'field_680b0101',
		'support'    => 'field_680b0102',
		'deliv'      => 'field_680b0013',
		'deliv_t'    => 'field_680b0014',
		'deliv_d'    => 'field_680b0015',
		'steps'      => 'field_680b0018',
		'steps_t'    => 'field_680b0019',
		'steps_d'    => 'field_680b0020',
		'stats'      => 'field_680b0023',
		'stats_v'    => 'field_680b0124',
		'stats_l'    => 'field_680b0026',
	];

	$report = [];
	foreach ( vc_seed_services_content() as $slug => $content ) {
		$term = get_term_by( 'slug', $slug, 'service' );
		if ( ! $term ) {
			$report[ $slug ] = [ 'error' => 'Term not found — check the service taxonomy terms exist.' ];
			continue;
		}

		$acf_id = 'service_' . $term->term_id;
		$rows   = [];

		$seed_simple = function ( $meta_name, $field_key, $value ) use ( $term, $acf_id, $overwrite, &$rows ) {
			$existing = get_term_meta( $term->term_id, $meta_name, true );
			if ( $existing && ! $overwrite ) {
				$rows[ $meta_name ] = 'kept existing';
				return;
			}
			update_field( $field_key, $value, $acf_id );
			$rows[ $meta_name ] = $existing ? 'overwritten' : 'seeded';
		};

		$seed_simple( 'sv_intro_statement', $keys['statement'], $content['statement'] );
		$seed_simple( 'sv_intro_support', $keys['support'], $content['support'] );

		$seed_repeater = function ( $meta_name, $field_key, $sub_keys, $items ) use ( $term, $acf_id, $overwrite, &$rows ) {
			$existing = (int) get_term_meta( $term->term_id, $meta_name, true );
			if ( $existing > 0 && ! $overwrite ) {
				$rows[ $meta_name ] = 'kept existing (' . $existing . ' rows)';
				return;
			}
			$value = array_map( function ( $item ) use ( $sub_keys ) {
				return array_combine( $sub_keys, $item );
			}, $items );
			update_field( $field_key, $value, $acf_id );
			$rows[ $meta_name ] = ( $existing > 0 ? 'overwritten' : 'seeded' ) . ' (' . count( $items ) . ' rows)';
		};

		$seed_repeater( 'sv_deliverables', $keys['deliv'], [ $keys['deliv_t'], $keys['deliv_d'] ], $content['deliverables'] );
		$seed_repeater( 'sv_process_steps', $keys['steps'], [ $keys['steps_t'], $keys['steps_d'] ], $content['steps'] );

		if ( ! empty( $content['stats'] ) ) {
			$seed_repeater( 'sv_results_stats', $keys['stats'], [ $keys['stats_v'], $keys['stats_l'] ], $content['stats'] );
			update_term_meta( $term->term_id, '_vc_sample_content', 1 );
		}

		// Yoast description lives in its own option; a no-op term save makes
		// Yoast rebuild the indexable it actually serves the meta from.
		if ( ! empty( $content['seo_desc'] ) ) {
			$tax_meta = get_option( 'wpseo_taxonomy_meta', [] );
			$existing_desc = $tax_meta['service'][ $term->term_id ]['wpseo_desc'] ?? '';
			if ( $existing_desc && ! $overwrite ) {
				$rows['seo description'] = 'kept existing';
			} else {
				$tax_meta['service'][ $term->term_id ]['wpseo_desc'] = $content['seo_desc'];
				update_option( 'wpseo_taxonomy_meta', $tax_meta );
				wp_update_term( $term->term_id, 'service', [ 'name' => $term->name ] );
				$rows['seo description'] = $existing_desc ? 'overwritten' : 'seeded';
			}
		}

		$report[ $slug ] = $rows;
	}

	return $report;
}

function vc_seed_services_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sorry, you are not allowed to access this page.' );
	}

	$report = null;
	if ( isset( $_POST['vc_seed_services'] ) ) {
		check_admin_referer( 'vc_seed_services' );
		$report = vc_seed_services_run( ! empty( $_POST['vc_seed_overwrite'] ) );
	}

	$services = vc_seed_services_content();
	?>
	<div class="wrap">
		<h1>Seed Services</h1>
		<p>Fills the six service pages' term content in one click: intro statement and support, the six deliverables, the process steps and the Yoast search description per service (plus the <code>[SAMPLE]</code> stats on Web Design &amp; Development, pending real figures). Terms are matched by slug. Headings and hub copy are not seeded: they already ship as ACF defaults and template fallbacks.</p>
		<p><strong>By default nothing is overwritten:</strong> fields that already have content are left exactly as they are.</p>

		<form method="post">
			<?php wp_nonce_field( 'vc_seed_services' ); ?>
			<label style="display:block;margin-bottom:12px;">
				<input type="checkbox" name="vc_seed_overwrite" value="1">
				Overwrite fields that already have content
			</label>
			<?php // Plain markup rather than submit_button(): no wp-admin include dependency. ?>
			<p class="submit">
				<button type="submit" class="button button-primary" name="vc_seed_services" value="1">Seed service content</button>
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
			<?php foreach ( $services as $slug => $content ) : ?>
				<li><code><?php echo esc_html( $slug ); ?></code>: intro, <?php echo count( $content['deliverables'] ); ?> deliverables, <?php echo count( $content['steps'] ); ?> steps<?php echo ! empty( $content['stats'] ) ? ', ' . count( $content['stats'] ) . ' sample stats' : ''; ?>, SEO description</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
