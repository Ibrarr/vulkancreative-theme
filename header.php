<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php vc_schema_type(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="custom-cursor"></div>

<div id="wrapper" class="hfeed">
    <header id="header" role="banner" class="<?php if ( is_front_page() ) { echo 'hero-active'; }; ?>">
        <div class="container px-4">
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
            </div>
        </div>
    </header>
    <main id="content" role="main">