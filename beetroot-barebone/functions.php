<?php
/**
 * Beetroot functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

// # Load modules

// Clean up theme
require_once __DIR__.'/lib/cleanup.php';

// Enqueue styles and scripts
require_once __DIR__.'/lib/enqueue-scripts.php';

// Implement the Custom Header feature.
require_once __DIR__.'/lib/framework.php';

// Load theme support options
require_once __DIR__.'/lib/theme-support.php';

// Custom template tags for this theme.
require_once __DIR__.'/lib/template-tags.php';

// Menu areas
require_once __DIR__.'/lib/menu-areas.php';

// Widget areas
require_once __DIR__.'/lib/widget-areas.php';

// Load Visual Composer shortcodes
require_once __DIR__.'/lib/vc_shortcodes.php';

// Load Jetpack compatibility file.
require_once __DIR__.'/lib/jetpack.php';


// Theme the TinyMCE editor
// You should create custom-editor-style.css in your theme folder
add_editor_style('custom-editor-style.css');


// Custom CSS for the login page
// Create wp-login.css in your theme folder
function loginCSS() {
    echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri('beetroot').'/wp-login.css"/>';
}
add_action('login_head', 'loginCSS');



function wpfme_has_sidebar($classes) {
    if (is_active_sidebar('sidebar')) {
        // add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }
    // return the $classes array
    return $classes;
}
add_filter('body_class','wpfme_has_sidebar');



// Remove the version number of WP
// Warning - this info is also available in the readme.html file in your root directory - delete this file!
remove_action('wp_head', 'wp_generator');


// Obscure login screen error messages
function wpfme_login_obscure(){ return '<strong>Sorry</strong>: Think you have gone wrong somwhere!';}
add_filter( 'login_errors', 'wpfme_login_obscure' );


// Disable the theme / plugin text editor in Admin
define('DISALLOW_FILE_EDIT', true);
