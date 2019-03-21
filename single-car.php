<?php
/**
 * The template for displaying showroom single posts.
 *
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="site-main" role="main">
            <?php
            echo '<div class="flex-container container">';
            echo '<div class="car-specs-container">';
            //Car Featured Image
            echo wp_get_attachment_image(get_field('featured_image'), 'medium');

            echo '<div class="specs-container">';
            //Car specs
            $args = array(
                'taxonomy' => 'car_brand_and_model',
                'hide_empty' => true,
                'number' => '1',
                'object_ids' => $post->ID,
                'parent' => '0'
            );
            $terms = get_terms($args);
            foreach ($terms as $term) {
                $args = array(
                    'taxonomy' => 'car_brand_and_model',
                    'hide_empty' => true,
                    'object_ids' => $post->ID,
                    'parent' => $term->term_id,
                );
                $child_terms = get_terms($args);
                foreach ($child_terms as $child_term) {
                    echo '<p>Brand and model: <a href="' . get_term_link($term, 'car_brand_and_model') . '">' . $term->name . '</a>, <a href="' . get_term_link($child_term, 'car_brand_and_model') . '">' . $child_term->name . '</a></p>';
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
            echo '<p>' .  get_field('mileage') . ' miles';
            echo '<p> $' . get_field('price');
            debug_to_console(get_post_permalink(get_field('showroom')->ID));
            echo '<p>Showroom: <a href="' . get_post_permalink(get_field('showroom')->ID) . '">' . get_field('showroom')->post_title . '</a></p>';
            echo '</div>';
            echo '</div>';

            $image_ids = get_field('gallery', false, false);
            $shortcode = '[' . 'gallery ids="' . implode(',', $image_ids) . '"]';
            echo do_shortcode($shortcode);

            echo get_field('description');
            echo '</div>';
            ?>

        </section><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
