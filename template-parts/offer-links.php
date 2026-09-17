<?php
/**
 * A quiet row pointing at the two standing offers, for visitors who are not
 * ready to brief a project: the free visibility report and the free website.
 * Both pages were otherwise reachable only from ads and search, so this is
 * also their internal link. An offer whose page is missing or unpublished
 * drops out, and with neither the row renders nothing.
 *
 * Args: 'lead' (string) overrides the opening line.
 */
$offer_lead  = $args['lead'] ?? 'Not sure where to start?';
$offer_pages = [
	'seo-ai-search'                   => 'Get a Free Visibility Report',
	'free-website-for-small-business' => 'See the Free Website Offer',
];

$offer_links = [];
foreach ( $offer_pages as $offer_slug => $offer_label ) {
	$offer_page = get_page_by_path( $offer_slug );
	if ( $offer_page && 'publish' === $offer_page->post_status && (int) $offer_page->ID !== (int) get_queried_object_id() ) {
		$offer_links[] = [ 'url' => get_permalink( $offer_page ), 'label' => $offer_label ];
	}
}
if ( ! $offer_links ) {
	return;
}
?>
<div class="offer-links">
	<p class="offer-links-lead"><?php echo esc_html( $offer_lead ); ?></p>
	<ul class="offer-links-list">
		<?php foreach ( $offer_links as $offer_link ) : ?>
			<li>
				<a href="<?php echo esc_url( $offer_link['url'] ); ?>"><?php echo esc_html( $offer_link['label'] ); ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
