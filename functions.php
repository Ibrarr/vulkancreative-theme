<?php

// Define Globals
define( 'VC_THEME_PREFIX', 'vc' );
define( 'VC_TEMPLATE_URI', get_template_directory_uri() );
define( 'VC_TEMPLATE_DIR', get_template_directory() );
define( 'VC_INC_PATH', VC_TEMPLATE_DIR . '/inc' );

define( 'DISALLOW_FILE_EDIT', true );

define('VC_NGROK', 'https://3ec3-45-145-28-4.ngrok-free.app/wp-content/themes/dc-theme');

// Actions
require VC_INC_PATH . '/actions.php';

// Filters
require VC_INC_PATH . '/filters.php';

// Remove Functions
require VC_INC_PATH . '/remove.php';

// Style and Scripts
require VC_INC_PATH . '/styles-scripts.php';

// Template Functions
require VC_INC_PATH . '/template-functions.php';

// Custom Post Types
require VC_INC_PATH . '/custom-post-types.php';

// Custom Taxonomies
require VC_INC_PATH . '/custom-taxonomies.php';

// ACF
require VC_INC_PATH . '/acf.php';

// Shortcodes
require VC_INC_PATH . '/shortcodes.php';

// Ajax Calls
require VC_INC_PATH . '/ajax-calls.php';