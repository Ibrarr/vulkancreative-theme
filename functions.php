<?php

// Define Globals
define( 'VC_THEME_PREFIX', 'vc' );
define( 'VC_TEMPLATE_URI', get_template_directory_uri() );
define( 'VC_TEMPLATE_DIR', get_template_directory() );
define( 'VC_INC_PATH', VC_TEMPLATE_DIR . '/inc' );

define( 'DISALLOW_FILE_EDIT', true );

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

// Service content seeder (Settings > Seed Services)
require VC_INC_PATH . '/service-seed.php';

// Work content seeder (Settings > Seed Work)
require VC_INC_PATH . '/project-seed.php';

// Case study content seeder (Settings > Seed Case Studies)
require VC_INC_PATH . '/case-study-seed.php';

// ACF
require VC_INC_PATH . '/acf.php';

// Shortcodes
require VC_INC_PATH . '/shortcodes.php';

// Ajax Calls
require VC_INC_PATH . '/ajax-calls.php';

// Gravity Forms (multi-step enquiry form hooks)
require VC_INC_PATH . '/gravity-forms.php';

// Landing Page flexible-content field group
require VC_INC_PATH . '/landing-page-fields.php';