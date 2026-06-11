<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php vc_schema_type(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script>document.documentElement.classList.add('js');</script>

    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Archivo-Variable.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-Regular.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-SemiBold.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-Bold.woff2'; ?>" as="font" type="font/woff2" crossorigin>

	<?php wp_head(); ?>

    <!-- Start cookieyes banner -->
    <script id="cookieyes" async type="text/javascript" src="https://cdn-cookieyes.com/client_data/8d9aad33234792349a620516/script.js"></script>
    <!-- End cookieyes banner -->

    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1622042995562129');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1622042995562129&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Meta Pixel Code -->
</head>
<body <?php body_class(); ?>>
<?php if ( ! is_page_template( 'page-templates/page-your-business.php' ) ) : ?>
<script>
    try {
        if (localStorage.getItem('darkMode') === 'disabled') {
            document.body.classList.remove('dark-mode');
        }
    } catch (e) {}
</script>
<?php endif; ?>
<?php wp_body_open(); ?>

<div id="wrapper" class="hfeed">
    <header id="header" role="banner" class="<?php if ( is_front_page() ) { echo 'hero-active'; }; ?>">
        <div class="<?php if ( is_page_template( 'page-templates/page-your-business.php' ) ) { echo 'container '; } ?>px-4">
            <div class="main-menu">
                <div class="logo">
                    <a href="/" aria-label="Home">
                        <?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/logos/logo.svg' ) ?>
                    </a>
                </div>
                <?php if ( ! is_page_template( 'page-templates/page-your-business.php' ) ) : ?>
                <div class="menu-theme-toggle">
                    <nav id="nav" role="navigation" itemscope
                         itemtype="https://schema.org/SiteNavigationElement">
                        <?php
                        if (is_front_page()) {
                            wp_nav_menu( array(
                                'theme_location' => 'main-menu-home',
                            ) );
                        } else {
                            wp_nav_menu( array(
                                'theme_location' => 'main-menu-other',
                            ) );
                        }
                        ?>
                    </nav>
                    <button type="button" class="theme-toggle" title="Toggle theme" aria-label="Toggle colour theme">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             aria-hidden="true"
                             width="2.5em"
                             height="2.5em"
                             fill="currentColor"
                             stroke-linecap="round"
                             class="theme-toggle__classic"
                             viewBox="0 0 32 32">
                            <defs>
                                <clipPath id="clip-path-1">
                                    <path d="M0-5h30a1 1 0 0 0 9 13v24H0Z" />
                                </clipPath>
                            </defs>
                            <g clip-path="url(#clip-path-1)">
                                <circle cx="50%" cy="50%" r="9.34" />
                                <circle cx="50%" cy="50%" r="6.34" />
                                <g stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 5.5v-4" />
                                    <path d="M16 30.5v-4" />
                                    <path d="M1.5 16h4" />
                                    <path d="M26.5 16h4" />
                                    <path d="m23.4 8.6 2.8-2.8" />
                                    <path d="m5.7 26.3 2.9-2.9" />
                                    <path d="m5.8 5.8 2.8 2.8" />
                                    <path d="m23.4 23.4 2.9 2.9" />
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
                <button type="button" class="mobile-menu-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
                    <span class="open"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/menu.svg' ) ?></span>
                    <span class="close"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/cross.svg' ) ?></span>
                </button>
                <?php endif; ?>
            </div>

            <?php if ( ! is_page_template( 'page-templates/page-your-business.php' ) ) : ?>
            <div class="mobile-menu" id="mobile-menu">
                <div class="px-4 menu-theme-mobile">
                    <nav id="nav-mobile" role="navigation" itemscope
                         itemtype="https://schema.org/SiteNavigationElement">
                        <?php
                        if (is_front_page()) {
                            wp_nav_menu( array(
                                'theme_location' => 'main-menu-home',
                            ) );
                        } else {
                            wp_nav_menu( array(
                                'theme_location' => 'main-menu-other',
                            ) );
                        }
                        ?>
                    </nav>
                    <div class="mobile-menu-extras">
                        <button type="button" class="theme-toggle" title="Toggle theme" aria-label="Toggle colour theme">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 aria-hidden="true"
                                 width="2em"
                                 height="2em"
                                 fill="currentColor"
                                 stroke-linecap="round"
                                 class="theme-toggle__classic"
                                 viewBox="0 0 32 32">
                                <defs>
                                    <clipPath id="clip-path-2">
                                        <path d="M0-5h30a1 1 0 0 0 9 13v24H0Z" />
                                    </clipPath>
                                </defs>
                                <g clip-path="url(#clip-path-2)">
                                    <circle cx="50%" cy="50%" r="9.34" />
                                    <circle cx="50%" cy="50%" r="6.34" />
                                    <g stroke="currentColor" stroke-width="1.5">
                                        <path d="M16 5.5v-4" />
                                        <path d="M16 30.5v-4" />
                                        <path d="M1.5 16h4" />
                                        <path d="M26.5 16h4" />
                                        <path d="m23.4 8.6 2.8-2.8" />
                                        <path d="m5.7 26.3 2.9-2.9" />
                                        <path d="m5.8 5.8 2.8 2.8" />
                                        <path d="m23.4 23.4 2.9 2.9" />
                                    </g>
                                </g>
                            </svg>
                        </button>
                        <div class="mobile-socials">
                            <a href="https://www.linkedin.com/company/vulkan-creative/" target="_blank" rel="noopener" aria-label="LinkedIn"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/linkedin.svg' ) ?></a>
                            <a href="https://www.tiktok.com/@vulkancreative" target="_blank" rel="noopener" aria-label="TikTok"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/tiktok.svg' ) ?></a>
                            <a href="https://www.instagram.com/vulkancreative/" target="_blank" rel="noopener" aria-label="Instagram"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/instagram.svg' ) ?></a>
                            <a href="https://www.youtube.com/@VulkanCreative" target="_blank" rel="noopener" aria-label="YouTube"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/socials/youtube.svg' ) ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </header>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main id="content" role="main">