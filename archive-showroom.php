<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="site-main container" role="main">

            <?php
            if (have_posts()) : ?>

                <?php
                /* Start the Loop */
                while (have_posts()) : the_post();
                    echo '<a href="' . get_post_permalink() . '">';
                    echo '<br>' . get_the_title();
                    echo '</a>';
                endwhile;


            endif; ?>

        </section><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
