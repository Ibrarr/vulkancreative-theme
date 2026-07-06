                </main>
            <footer id="footer" role="contentinfo">
                <div class="container px-4">
                    <?php
                    // No CTA band where the page already ends on its own
                    // conversion moment: the Contact page, the services hub,
                    // the free-website offer page and the your-business landing
                    // page end in their own enquiry form/CTA; service pages,
                    // single work pages and single case studies end in their
                    // own CTA band. The work and case-studies archives keep it:
                    // the grids have no closing moment of their own.
                    $vc_hide_footer_cta = is_page_template( 'page-templates/page-contact-us.php' )
                        || is_page_template( 'page-templates/page-services-hub.php' )
                        || is_page_template( 'page-templates/page-free-website.php' )
                        || is_page_template( 'page-templates/page-your-business.php' )
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
                        // Services menu: the service terms in their configured order,
                        // rendered straight from the taxonomy (no admin menu to keep in sync).
                        $footer_services = get_terms( array( 'taxonomy' => 'service', 'hide_empty' => false ) );
                        if ( ! is_wp_error( $footer_services ) && $footer_services ) {
                            foreach ( $footer_services as $fs_key => $fs_term ) {
                                $footer_services[ $fs_key ]->order = (int) get_field( 'order', 'service_' . $fs_term->term_id );
                            }
                            usort( $footer_services, function ( $a, $b ) { return $a->order - $b->order; } );
                        } else {
                            $footer_services = array();
                        }
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