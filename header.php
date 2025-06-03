<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php vc_schema_type(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-Regular.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-SemiBold.woff2'; ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo VC_TEMPLATE_URI . '/assets/fonts/Poppins-Bold.woff2'; ?>" as="font" type="font/woff2" crossorigin>

	<?php wp_head(); ?>

    <!-- Start cookieyes banner -->
    <script id="cookieyes" async type="text/javascript" src="https://cdn-cookieyes.com/client_data/8d9aad33234792349a620516/script.js"></script>
    <!-- End cookieyes banner -->
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
                <nav id="nav" role="navigation" itemscope
                     itemtype="https://schema.org/SiteNavigationElement">
                    <?php wp_nav_menu( array(
                        'theme_location' => 'main-menu',
                    ) ); ?>
                </nav>
                <div class="mobile-menu-icons">
                    <div class="open"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/menu.svg' ) ?></div>
                    <div class="close"><?php echo file_get_contents( VC_TEMPLATE_DIR . '/assets/images/icons/cross.svg' ) ?></div>
                </div>
            </div>

            <div class="mobile-menu">
                <div class="px-4">
                    <nav id="nav" role="navigation" itemscope
                         itemtype="https://schema.org/SiteNavigationElement">
                        <?php wp_nav_menu( array(
                            'theme_location' => 'main-menu',
                        ) ); ?>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main id="content" role="main">