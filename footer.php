                </main>
            <footer id="footer" role="contentinfo">
                <div class="container px-4">
                    <div class="row">
                        <div class="col-lg-6 left">
                            <div class="footer-logo"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/logos/logo.svg' ) ?></div>
                            <div class="form">
                                <p>Subscribe to our newsletter for the latest updates.</p>
                                <?php echo do_shortcode( '[gravityform id="3" title="false" description="false" ajax="true"]' ); ?>
                            </div>
                        </div>
                        <div class="col-lg-2 offset-lg-2 footer-menu">
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
                        <div class="col-lg-2 socials">
                            <div class="social-inner">
                                <a href="https://www.linkedin.com/company/vulkan-creative/" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/linkedin.svg' ) ?></i> LinkedIn</a>
                                <a href="https://www.tiktok.com/@vulkancreative" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/tiktok.svg' ) ?></i> TikTok</a>
                                <a href="https://www.instagram.com/vulkancreative/" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/instagram.svg' ) ?></i> Instagram</a>
                                <a href="https://www.youtube.com/@VulkanCreative" target="_blank"><i><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/youtube.svg' ) ?></i> YouTube</a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="bottom">
                                <p>© 2025 Vulkan Creative. All rights reserved.</p>
                                <div class="legal">
                                    <a href="/privacy-policy">Privacy Policy</a>
                                    <a href="/cookie-policy">Cookie Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>