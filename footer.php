<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

?>

</main><!-- #content -->

<footer id="footer-container" class="site-footer" role="contentinfo">
    <?php
    echo do_shortcode(get_field('contact_form_shortcode', 'option'));
    echo '<p class="copyright">' . get_field('copyright', 'option') . '</p>';
    ?>
</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>
