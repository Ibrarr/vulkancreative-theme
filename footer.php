                </main>
            <footer id="footer" role="contentinfo">
                <div class="container px-4">
                    <?php
                    // No CTA band where the page already ends on its own
                    // conversion moment: the Contact page and the services
                    // hub end in the enquiry form, service pages and single
                    // work pages end in their own CTA band. The work archive
                    // keeps it: the grid has no closing moment of its own.
                    $vc_hide_footer_cta = is_page_template( 'page-templates/page-contact-us.php' )
                        || is_page_template( 'page-templates/page-services-hub.php' )
                        || is_tax( 'service' )
                        || is_singular( 'project' );
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
                        <div class="col-lg-4 left">
                            <div class="footer-logo"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/logos/logo.svg' ) ?></div>
                            <p class="footer-line">Brand, web and marketing, built in person and measured by results.</p>
                        </div>
                        <div class="col-lg-2 offset-lg-1 footer-menu">
                            <p class="footer-heading">Explore</p>
                            <nav id="footer-menu" role="navigation" itemscope
                                 itemtype="https://schema.org/SiteNavigationElement">
                                <?php
                                wp_nav_menu( array(
                                    'theme_location' => 'footer-menu',
                                ) );
                                ?>
                            </nav>
                        </div>
                        <?php
                        $footer_email      = get_field( 'company_email', 'options' ) ?: 'info@vulkancreative.com';
                        $footer_phone      = get_field( 'company_phone', 'options' ) ?: '020 3576 7525';
                        $footer_phone_href = $footer_phone ? preg_replace( '/[^0-9+]/', '', $footer_phone ) : '';
                        ?>
                        <div class="col-lg-3 footer-contact">
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
                        <div class="col-lg-2 socials">
                            <p class="footer-heading">Follow us</p>
                            <div class="social-inner">
                                <a href="https://www.linkedin.com/company/vulkan-creative/" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/linkedin.svg' ) ?></i> LinkedIn</a>
                                <a href="https://www.tiktok.com/@vulkancreative" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/tiktok.svg' ) ?></i> TikTok</a>
                                <a href="https://www.instagram.com/vulkancreative/" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/instagram.svg' ) ?></i> Instagram</a>
                                <a href="https://www.youtube.com/@VulkanCreative" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/youtube.svg' ) ?></i> YouTube</a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="bottom">
                                <p>© <?php echo date("Y"); ?> Vulkan Creative. All rights reserved.</p>
                                <div class="legal">
                                    <a href="/privacy-policy">Privacy Policy</a>
                                    <a href="/cookie-policy">Cookie Settings</a>
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