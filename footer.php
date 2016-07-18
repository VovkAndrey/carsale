<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Beetroot
 */

?>

</div><!-- #content -->

<footer id="footer-container" class="site-footer" role="contentinfo">
    <nav class="nav-footer">
        <?php
        if (has_nav_menu('primary')) :
            wp_nav_menu(['theme_location' => 'footer_menu', 'menu_id' => 'footer-menu', 'walker' => new beetroot_navwalker()]);
        endif;
        ?>
    </nav><!-- .nav-primary -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
