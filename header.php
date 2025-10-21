<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php vc_schema_type(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-Regular.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-SemiBold.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-Bold.woff2'; ?>" as="font" type="font/woff2" crossorigin>

    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark-mode');
        }
    </script>

	<?php wp_head(); ?>
    <link rel="icon" href="<?php echo esc_url( VC_TEMPLATE_URI . '/assets/images/logos/favicon.ico' ); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url( VC_TEMPLATE_URI . '/assets/images/logos/favicon.svg' ); ?>" sizes="any">

    <!-- Start cookieyes banner -->
    <script id="cookieyes" async type="text/javascript" src="https://cdn-cookieyes.com/client_data/8d9aad33234792349a620516/script.js"></script>
    <!-- End cookieyes banner -->

    <!-- Start active campaign tracking -->
    <script>
        (function(e,t,o,n,p,r,i){e.visitorGlobalObjectAlias=n;e[e.visitorGlobalObjectAlias]=e[e.visitorGlobalObjectAlias]||function(){(e[e.visitorGlobalObjectAlias].q=e[e.visitorGlobalObjectAlias].q||[]).push(arguments)};e[e.visitorGlobalObjectAlias].l=(new Date).getTime();r=t.createElement("script");r.src=o;r.async=true;i=t.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)})(window,document,"https://diffuser-cdn.app-us1.com/diffuser/diffuser.js","vgo");
        vgo('setAccount', '802279391');
        vgo('setTrackByDefault', true);

        vgo('process');
    </script>
    <!-- End active campaign tracking -->
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="custom-cursor"></div>

<div id="wrapper" class="hfeed">
    <header id="header" role="banner" class="<?php if ( is_front_page() ) { echo 'hero-active'; }; ?>">
        <div class="px-4">
            <div class="main-menu">
                <div class="logo">
                    <a href="/" aria-label="Home">
                        <?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/logos/logo.svg' ) ?>
                    </a>
                </div>
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
                    <div class="theme-toggle disable-custom-cursor" title="Toggle theme">
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
                    </div>
                </div>
                <div class="mobile-menu-icons">
                    <div class="open"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/menu.svg' ) ?></div>
                    <div class="close"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/cross.svg' ) ?></div>
                </div>
            </div>

            <div class="mobile-menu">
                <div class="px-4 menu-theme-mobile">
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
                    <div class="theme-toggle disable-custom-cursor" title="Toggle theme">
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
                    </div>

                </div>
            </div>
        </div>
    </header>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main id="content" role="main">