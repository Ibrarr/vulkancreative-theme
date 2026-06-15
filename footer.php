                </main>
            <footer id="footer" role="contentinfo">
                <div class="container px-4">
                    <div class="row footer-cta">
                        <div class="col-lg-8">
                            <p class="footer-cta-heading">Ready to forge something that <span>performs</span>?</p>
                        </div>
                        <div class="col-lg-4 footer-cta-action">
                            <a class="button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Start a project</a>
                        </div>
                    </div>
                    <div class="row footer-main">
                        <div class="col-lg-5 left">
                            <div class="footer-logo"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/logos/logo.svg' ) ?></div>
                            <p class="footer-line">Brand, web and marketing, built in person and measured by results.</p>
                        </div>
                        <div class="col-lg-3 offset-lg-1 footer-menu">
                            <p class="footer-heading">Explore</p>
                            <nav id="footer-menu" role="navigation" itemscope
                                 itemtype="https://schema.org/SiteNavigationElement">
                                <?php
                                if (is_front_page()) {
                                    wp_nav_menu( array(
                                        'theme_location' => 'footer-menu-home',
                                    ) );
                                } else {
                                    wp_nav_menu( array(
                                        'theme_location' => 'footer-menu-other',
                                    ) );
                                }
                                ?>
                            </nav>
                        </div>
                        <div class="col-lg-3 socials">
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