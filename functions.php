<?php

// Define Globals
define( 'DC_THEME_PREFIX', 'dc' );
define( 'DC_TEMPLATE_URI', get_template_directory_uri() );
define( 'DC_TEMPLATE_DIR', get_template_directory() );
define( 'DC_INC_PATH', DC_TEMPLATE_DIR . '/inc' );

define( 'DISALLOW_FILE_EDIT', true );

define('DC_NGROK', 'https://3ec3-45-145-28-4.ngrok-free.app/wp-content/themes/dc-theme');

// Actions
require DC_INC_PATH . '/actions.php';

// Filters
require DC_INC_PATH . '/filters.php';

// Remove Functions
require DC_INC_PATH . '/remove.php';

// Style and Scripts
require DC_INC_PATH . '/styles-scripts.php';

// Template Functions
require DC_INC_PATH . '/template-functions.php';

// Custom Post Types
require DC_INC_PATH . '/custom-post-types.php';

// Custom Taxonomies
require DC_INC_PATH . '/custom-taxonomies.php';

// ACF
require DC_INC_PATH . '/acf.php';

// Shortcodes
require DC_INC_PATH . '/shortcodes.php';

// Ajax Calls
require DC_INC_PATH . '/ajax-calls.php';