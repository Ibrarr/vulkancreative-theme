                </main>
            <footer id="footer" role="contentinfo">
                <div class="container px-4">
                    <?php
                    // No CTA band where the page already ends on its own
                    // conversion moment: the Contact page, the services hub,
                    // the free-website offer page and the reusable landing
                    // template end in their own enquiry form/CTA; service pages,
                    // single work pages and single case studies end in their
                    // own CTA band. The work and case-studies archives keep it:
                    // the grids have no closing moment of their own.
                    $vc_hide_footer_cta = is_page_template( 'page-templates/page-contact-us.php' )
                        || is_page_template( 'page-templates/page-services-hub.php' )
                        || is_page_template( 'page-templates/page-free-website.php' )
                        || is_page_template( 'page-templates/page-landing-page.php' )
                        || is_tax( 'service' )
                        || is_singular( 'project' )
                        || is_singular( 'case_study' );
                    ?>
                    <?php if ( ! $vc_hide_footer_cta ) : ?>
                    <div class="row footer-cta">
                        <div class="col-lg-8">
                            <p class="footer-cta-heading">Ready to forge something that <span>performs</span>?</p>
                        </div>
                        <div class="col-lg-4 footer-cta-action">
                            <a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a Project</a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="row footer-main">
                        <div class="col-lg-5 left">
                            <div class="footer-logo"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/logos/logo.svg' ) ?></div>
                            <p class="footer-line">Brand, web and marketing that performs, built in-house and measured by results.</p>
                            <div class="footer-socials">
                                <a href="https://www.linkedin.com/company/vulkan-creative/" target="_blank" rel="noopener" aria-label="Vulkan Creative on LinkedIn"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/linkedin.svg' ) ?></a>
                                <a href="https://www.tiktok.com/@vulkancreative" target="_blank" rel="noopener" aria-label="Vulkan Creative on TikTok"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/tiktok.svg' ) ?></a>
                                <a href="https://www.instagram.com/vulkancreative/" target="_blank" rel="noopener" aria-label="Vulkan Creative on Instagram"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/instagram.svg' ) ?></a>
                                <a href="https://www.youtube.com/@VulkanCreative" target="_blank" rel="noopener" aria-label="Vulkan Creative on YouTube"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/youtube.svg' ) ?></a>
                            </div>
                        </div>
                        <?php
                        // Services menu: the parent services in the order set in Global
                        // Settings > Service List (parents ticked to show in the footer).
                        $footer_services = vc_ordered_services( 'footer' );
                        ?>
                        <?php if ( $footer_services ) : ?>
                        <div class="col-lg-3 footer-menu footer-services">
                            <p class="footer-heading">Services</p>
                            <nav aria-label="Services" itemscope itemtype="https://schema.org/SiteNavigationElement">
                                <ul>
                                    <?php foreach ( $footer_services as $fs_term ) :
                                        $fs_link = get_term_link( $fs_term );
                                        if ( is_wp_error( $fs_link ) ) {
                                            continue;
                                        } ?>
                                        <li><a href="<?php echo esc_url( $fs_link ); ?>" itemprop="url"><?php echo esc_html( $fs_term->name ); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                        <?php endif; ?>
                        <div class="col-lg-2 footer-menu">
                            <p class="footer-heading">Explore</p>
                            <nav id="footer-menu" role="navigation" itemscope
                                 itemtype="https://schema.org/SiteNavigationElement">
                                <?php
                                // The Explore list mirrors the header nav minus the services
                                // entry and the CTA button (stripped by the vc_footer_explore
                                // filter in inc/filters.php), so it stays in step with the header.
                                $GLOBALS['vc_footer_explore'] = true;
                                wp_nav_menu( array(
                                    'theme_location' => 'main-menu',
                                    'depth'          => 1,
                                    'container'      => false,
                                    'menu_id'        => 'menu-footer-explore',
                                ) );
                                $GLOBALS['vc_footer_explore'] = false;
                                ?>
                            </nav>
                        </div>
                        <?php
                        $footer_email      = get_field( 'company_email', 'options' ) ?: 'info@vulkancreative.com';
                        $footer_phone      = get_field( 'company_phone', 'options' ) ?: '020 3576 7525';
                        $footer_phone_href = $footer_phone ? preg_replace( '/[^0-9+]/', '', $footer_phone ) : '';
                        ?>
                        <div class="col-lg-2 footer-contact">
                            <p class="footer-heading">Contact</p>
                            <ul class="footer-contact-list">
                                <?php if ( $footer_email ) : ?>
                                    <li><a href="mailto:<?php echo esc_attr( $footer_email ); ?>"><?php echo esc_html( $footer_email ); ?></a></li>
                                <?php endif; ?>
                                <?php if ( $footer_phone ) : ?>
                                    <li><a href="tel:<?php echo esc_attr( $footer_phone_href ); ?>"><?php echo esc_html( $footer_phone ); ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php
                        // Quiet trust row: the press credit, the partner badges and the
                        // Google rating, all from Global Settings (Press Features / Partner
                        // Logos / Reviews via vc_google_reviews()). Three bottom-aligned
                        // groups on one hairline at lg+, stacked below. Each group renders
                        // only with its own data; the row renders when any group has some.
                        $footer_gr       = vc_google_reviews();
                        $footer_gr_ok    = $footer_gr['count'] > 0 && $footer_gr['url'];
                        $footer_press    = have_rows( 'press_features', 'options' );
                        $footer_partners = (bool) get_field( 'partner_logos', 'options' );
                        ?>
                        <?php if ( $footer_press || $footer_partners || $footer_gr_ok ) : ?>
                            <div class="col-lg-12">
                                <div class="footer-trust">
                                    <?php if ( $footer_press ) : ?>
                                        <div class="footer-press">
                                            <p class="footer-heading">As featured in</p>
                                            <div class="footer-press-logos">
                                                <?php while ( have_rows( 'press_features', 'options' ) ) : the_row();
                                                    $ft_logo = get_sub_field( 'logo' );
                                                    $ft_name = get_sub_field( 'name' );
                                                    $ft_link = get_sub_field( 'url' );
                                                    if ( ! $ft_logo ) { continue; }
                                                    $ft_img = '<img loading="lazy" src="' . esc_url( $ft_logo['url'] ) . '" alt="' . esc_attr( $ft_name ?: ( $ft_logo['alt'] ?: $ft_logo['title'] ) ) . '" width="' . (int) $ft_logo['width'] . '" height="' . (int) $ft_logo['height'] . '">';
                                                    if ( $ft_link ) : ?>
                                                        <a class="footer-press-logo" href="<?php echo esc_url( $ft_link ); ?>" target="_blank" rel="noopener"><?php echo $ft_img; ?></a>
                                                    <?php else : ?>
                                                        <span class="footer-press-logo"><?php echo $ft_img; ?></span>
                                                    <?php endif;
                                                endwhile; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( $footer_partners ) : ?>
                                        <div class="footer-partners">
                                            <?php get_template_part( 'template-parts/partner-logos', null, [ 'variant' => 'footer' ] ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( $footer_gr_ok ) : ?>
                                        <a class="footer-google" href="<?php echo esc_url( $footer_gr['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $footer_gr['rating'] . ' rating, ' . $footer_gr['count'] . ( 1 === (int) $footer_gr['count'] ? ' Google Review' : ' Google Reviews' ) . '. Opens our Google profile in a new tab.' ); ?>">
                                            <img src="<?php echo esc_url( VC_TEMPLATE_URI . '/assets/images/logos/google-g.webp' ); ?>" alt="" width="20" height="20">
                                            <span aria-hidden="true"><span class="footer-google-value"><?php echo esc_html( $footer_gr['rating'] ); ?></span><span class="footer-google-dot">·</span><?php echo (int) $footer_gr['count']; ?> <?php echo 1 === (int) $footer_gr['count'] ? 'Google Review' : 'Google Reviews'; ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-lg-12">
                            <div class="bottom">
                                <p>© <?php echo date("Y"); ?> Vulkan Creative. All rights reserved.</p>
                                <div class="legal">
                                    <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>
                                    <a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>">Cookie Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-wordmark" aria-hidden="true">Vulkan</div>
            </footer>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>