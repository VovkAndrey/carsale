<?php
/**
 * Register navigation menus
 *
 * @link https://codex.wordpress.org/Function_Reference/register_nav_menus
 * @since Beetroot 1.0.0
 */
add_action( 'after_setup_theme', 'register_theme_menus' );
function register_theme_menus() {
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'beetroot' ),
        'footer_menu' => __( 'Footer Menu', 'beetroot' )
    ) );
}

