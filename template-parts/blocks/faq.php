<?php
/**
 * Block: FAQ. Accessible disclosure accordion; server-renders open, collapsed
 * by landing/faq.js. Ids scoped by block index so repeated FAQ blocks stay
 * unique. Q&A also feeds the aggregated FAQPage JSON-LD (in the template).
 */
$surface = $args['surface'] ?? 'a';
$heading = $args['heading'] ?? '';
$cl      = $args['cta_label'] ?? '';
$ct      = $args['cta_target'] ?? '#lp-cta';
$faqs    = $args['faqs'] ?? [];
$bi      = $args['index'] ?? 0;
if ( empty( $faqs ) ) { return; }
?>
<section class="lp-faq is-surface-<?php echo esc_attr( $surface ); ?>">
	<div class="container px-4">
		<div class="row gx-5">
			<div class="col-lg-4">
				<div class="lp-faq-head">
					<div class="content">
						<h2 data-reveal="heading"><?php echo wp_kses_post( $heading ); ?></h2>
					</div>
					<?php if ( $cl ) : ?>
						<a class="button-ghost" href="<?php echo esc_attr( $ct ); ?>"><?php echo esc_html( $cl ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<div class="lp-faq-list" data-reveal="stagger">
					<?php foreach ( $faqs as $i => $faq ) :
						$q_id = 'lp-faq-' . $bi . '-q-' . ( $i + 1 );
						$a_id = 'lp-faq-' . $bi . '-a-' . ( $i + 1 );
						?>
						<div class="lp-faq-item">
							<h3 class="faq-q">
								<button type="button" class="faq-toggle" id="<?php echo esc_attr( $q_id ); ?>" aria-expanded="true" aria-controls="<?php echo esc_attr( $a_id ); ?>">
									<span class="faq-q-text"><?php echo esc_html( $faq['question'] ); ?></span>
									<span class="faq-icon" aria-hidden="true"></span>
								</button>
							</h3>
							<div class="faq-a" id="<?php echo esc_attr( $a_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $q_id ); ?>">
								<div class="faq-a-inner"><?php echo wpautop( esc_html( $faq['answer'] ) ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
