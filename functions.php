<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/**
 * Text domain definition
 */
defined('THEME_TD') ? THEME_TD : define('THEME_TD', 'carsale');

// Load modules

$theme_includes = [
    '/lib/helpers.php',
    '/lib/cleanup.php',                        // Clean up default theme includes
    '/lib/enqueue-scripts.php',                // Enqueue styles and scripts
    '/lib/protocol-relative-theme-assets.php', // Protocol (http/https) relative assets path
    '/lib/framework.php',                      // Css framework related stuff (content width, nav walker class, comments, pagination, etc.)
    '/lib/theme-support.php',                  // Theme support options
    '/lib/template-tags.php',                  // Custom template tags
    '/lib/menu-areas.php',                     // Menu areas
    '/lib/widget-areas.php',                   // Widget areas
    '/lib/customizer.php',                     // Theme customizer
    '/lib/vc_shortcodes.php',                  // Visual Composer shortcodes
    '/lib/jetpack.php',                        // Jetpack compatibility file
    '/lib/acf_field_groups_type.php',          // ACF Field Groups Organizer
];

foreach ($theme_includes as $file) {
    if (!$filepath = locate_template($file)) {
        continue;
        trigger_error(sprintf(__('Error locating %s for inclusion', THEME_TD), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);


// Theme the TinyMCE editor (Copy post/page text styles in this file)

add_editor_style('assets/dist/css/custom-editor-style.css');


// Custom CSS for the login page

function loginCSS()
{
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri(THEME_TD) . 'assets/dist/css/wp-login.css"/>';
}

add_action('login_head', 'loginCSS');


// Add body class for active sidebar
function wp_has_sidebar($classes)
{
    if (is_active_sidebar('sidebar')) {
        // add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }
    // return the $classes array
    return $classes;
}

add_filter('body_class', 'wp_has_sidebar');

// Remove the version number of WP
// Warning - this info is also available in the readme.html file in your root directory - delete this file!
remove_action('wp_head', 'wp_generator');


// Obscure login screen error messages
function wp_login_obscure()
{
    return '<strong>Error</strong>: wrong username or password';
}

add_filter('login_errors', 'wp_login_obscure');


// Disable the theme / plugin text editor in Admin
define('DISALLOW_FILE_EDIT', true);

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

function add_light_gallery_attribute($link)
{
    return str_replace('>', ' data-lightbox="image">', $link);
}

add_filter('wp_get_attachment_link', 'add_light_gallery_attribute', 10, 2);

function show_car_block($args)
{
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) :
            $the_query->the_post();
            echo '<a href="' . get_post_permalink() . '">';
            echo '<div class="showroom__item">';
            $args = array(
                'taxonomy' => 'car_brand_and_model',
                'hide_empty' => true,
                'number' => '1',
                'object_ids' => $post->ID,
                'parent' => '0'
            );
            $terms = get_terms($args);
            foreach ($terms as $term) {
                $child_args = array(
                    'taxonomy' => 'car_brand_and_model',
                    'hide_empty' => true,
                    'object_ids' => $post->ID,
                    'parent' => $term->term_id,
                );
                $child_terms = get_terms($child_args);
                foreach ($child_terms as $child_term) {
                    echo '<p>' . $term->name . ' ' . $child_term->name . ' </p>';
                }
            }

            $args = array(
                'taxonomy' => 'car_engine',
                'hide_empty' => true,
                'number' => '1',
                'object_ids' => $post->ID,
            );
            $terms = get_terms($args);
            echo '<p>' . $terms[0]->name . '</p>';
            echo '<p>' . get_field('mileage') . ' miles';
            echo '<p> $' . get_field('price');
            echo '</div>';
            echo '</a>';
        endwhile;
        wp_reset_query();
    endif;
}