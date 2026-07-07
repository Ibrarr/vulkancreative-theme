<?php
/**
 * Block: checklist / what you get. Plate of rows with a quiet stamp, optional
 * big red total. Rows + total animate in via landing/checklist.js.
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$sub     = $args['subheading'] ?? '';
$stamp   = $args['stamp'] ?? '';
$items   = $args['items'] ?? [];
$show_total  = ! empty( $args['show_total'] );
$total_label = $args['total_label'] ?? '';
$total_value = $args['total_value'] ?? '';
$total_note  = $args['total_note'] ?? '';
if ( empty( $items ) ) { return; }
?>
<section class="lp-checklist is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-4">
				<div class="lp-checklist-head">
					<div class="content">
						<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
					</div>
					<?php if ( $sub ) : ?>
						<p class="sub-heading" data-reveal="fade"><?php echo esc_html( $sub ); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<div class="lp-checklist-plate">
					<ul class="checklist-list">
						<?php foreach ( $items as $row ) : ?>
							<li class="checklist-row">
								<span class="item-name"><?php echo esc_html( $row['item'] ); ?></span>
								<?php if ( $stamp ) : ?>
									<span class="checklist-stamp"><?php echo esc_html( $stamp ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $row['detail'] ) ) : ?>
									<span class="item-detail"><?php echo esc_html( $row['detail'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php if ( $show_total && ( $total_label || $total_value ) ) : ?>
						<div class="checklist-total">
							<span class="total-label"><?php echo esc_html( $total_label ); ?></span>
							<span class="total-value"><?php echo esc_html( $total_value ); ?></span>
						</div>
						<?php if ( $total_note ) : ?>
							<p class="checklist-note"><?php echo esc_html( $total_note ); ?></p>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
