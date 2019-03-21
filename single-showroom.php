<?php
/**
 * The template for displaying showrrom single posts.
 *
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="showroom-main" role="main">
            <?php

            echo '<section class="showroom-title-block">';
            //Showroom name
            echo '<h2>' . get_the_title() . '</h2>';

            //Showroom type
            $args = array(
                'taxonomy' => 'showroom_type',
                'object_ids' => $post->ID
            );
            $terms = get_terms($args);
            echo '<p>Type: ' . $terms[0]->name . '</p>';

            //Showroom services
            $args = array(
                'taxonomy' => 'showroom_services',
                'object_ids' => $post->ID
            );
            $terms = get_terms($args);
            $showroom_services = array();
            foreach ($terms as $term) {
                array_push($showroom_services, $term->name);
            };
            echo '<p>Services: ' . implode(', ', $showroom_services) . '</p>';
            echo '</section>';

            //Showroom location
            echo get_field('location');

            echo '<section class="showroom-managers-block">';
            //Showroom managers
            if (have_rows('manager')):
                while (have_rows('manager')) : the_row();
                    ?>
                    <div class="showroom-managers-block__item">
                        <?php echo wp_get_attachment_image(get_sub_field('photo'), 'thumbnail'); ?>
                        <p class="showroom-managers-block__item__name"><?php the_sub_field('name') ?><p/>
                        <p class="showroom-managers-block__item__email"><?php the_sub_field('email') ?><p/>
                        <p class="showroom-managers-block__item__phone"><?php the_sub_field('phone') ?><p/>
                    </div>
                <?php
                endwhile;
            endif;
            echo '</section>';

            $args = array(
                'post_type' => 'car',
                'meta_query' => array(
                    array(
                        'key' => 'showroom',
                        'value' => $post->ID,
                        'compare' => '='
                    )
                ),
                'number' => '1'
            );

            show_car_block($args);

            ?>

        </section><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
