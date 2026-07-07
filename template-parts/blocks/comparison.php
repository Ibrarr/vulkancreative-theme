<?php
/**
 * Block: comparison. Two clean columns — the usual way (muted, left) vs the
 * Vulkan way (red + bold, right). Stacks Vulkan-first below md.
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$note    = $args['note'] ?? '';
$ut      = $args['usual_title'] ?? '';
$vt      = $args['vulkan_title'] ?? '';
$rows    = $args['rows'] ?? [];
if ( empty( $rows ) ) { return; }
?>
<section class="lp-comparison is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<div class="row lp-split-head">
			<div class="col-lg-7">
				<div class="content">
					<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
				</div>
			</div>
			<?php if ( $note ) : ?>
				<div class="col-lg-4 offset-lg-1 lp-comparison-intro">
					<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $note ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<div class="compare-table" data-reveal="stagger">
			<div class="compare-row compare-row--head">
				<span class="cr-head cr-head--usual"><?php echo esc_html( $ut ); ?></span>
				<span class="cr-head cr-head--vulkan"><?php echo esc_html( $vt ); ?></span>
			</div>
			<?php foreach ( $rows as $row ) : ?>
				<div class="compare-row">
					<span class="cr-usual"><?php echo esc_html( $row['usual'] ); ?></span>
					<span class="cr-vulkan"><?php echo esc_html( $row['vulkan'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
