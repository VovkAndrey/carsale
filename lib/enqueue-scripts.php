<?php
/**
 * Enqueue all styles and scripts.
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style}
 */
if (!function_exists('beetroot_scripts')) :
    function beetroot_scripts()
    {

    // Enqueue the main Stylesheet.
    wp_enqueue_style('main-stylesheet', get_stylesheet_directory_uri().'/assets/dist/css/style.css', array(), '1.0.0', 'all');

    // Deregister the jquery version bundled with WordPress.
    wp_deregister_script('jquery');

    // CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-2.2.4.min.js', array(), '2.2.4', false);

    // Enqueue the main JS file.
    wp_enqueue_script('main-javascript', get_stylesheet_directory_uri().'/assets/dist/javascript/global.js', array('jquery'), '1.0.0', true);
    }

    add_action('wp_enqueue_scripts', 'beetroot_scripts');
endif;

// Add attributes to enqueued scripts <script> tag.
if (!function_exists('edit_scripts')) {
    function edit_scripts($url)
    {
        if (false === strpos($url, '.js')) {
            return $url;
        }
    /*
     * Use "integrity" and "crossorigin" attributes for jQuery CDN
     * The integrity and crossorigin attributes are used for Subresource Integrity (SRI) checking.
     * This allows browsers to ensure that resources hosted on third-party servers have not been tampered with.
     * Use of SRI is recommended as a best-practice, whenever libraries are loaded from a third-party source. Read more at srihash.org
     */
  if (strpos($url, 'jquery-3.0.0.min.js')) {
      return "$url' integrity='sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44='
			  crossorigin='anonymous";
  }

        return $url;
    }
    add_filter('clean_url', 'edit_scripts', 11, 1);
};
