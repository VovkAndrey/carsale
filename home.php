<?php
/**
 * Home template file.
 *
 * Template Name: Home
 */

get_header(); ?>
    <div id="primary" class="content-area">
        <section id="main" class="site-main" role="main">

            <!-- Top Slider -->
            <div class="top-slider">
                <?php
                if (have_rows('top_slider')):
                    while (have_rows('top_slider')) : the_row();
                        ?>
                        <div class="top-slider__item">
                            <img class="top-slider__item__image" src="<?php the_sub_field('top_slider_item_image') ?>"
                                 alt="Slider Image"/>
                            <div class="top-slider__item__overlay">
                                <p class="top-slider__item__text">  <?php the_sub_field('top_slider_item_text') ?>  <p/>
                            </div>
                        </div>
                    <?php
                    endwhile;
                endif;
                ?>
            </div>

            <!-- Showroom List -->
            <div class="showroom-wrap container">
                <?php
                $args = array(
                    'post_type' => 'showroom'
                );
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) :
                    while ($the_query->have_posts()) :
                        $the_query->the_post();
                        echo '<a href="' . get_post_permalink() . '">';
                        echo '<div class="showroom__item">';
                        the_title();
                        echo '</div>';
                        echo '</a>';
                    endwhile;
                    wp_reset_query();
                endif;
                ?>
            </div>

            <!-- Home Content -->
            <div class="home-content container">
                <?php
                echo get_field('home_content');
                ?>
            </div>

            <!-- Hot Car List -->
            <div class="showroom-wrap">
                <?php
                $args = array(
                    'post_type' => 'car',
                    'meta_query' => array(
                        array(
                            'key' => 'hot',
                            'value' => '1',
                            'compare' => 'LIKE'
                        )
                    )
                );

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
                            debug_to_console($term->name);
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
                        echo '<p>' .  get_field('mileage') . ' miles';
                        echo '<p> $' . get_field('price');
                        echo '</div>';
                        echo '</a>';
                    endwhile;
                    wp_reset_query();
                endif;
                ?>
            </div>

            <!-- Partners -->
            <div class="home-partners">
                <?php
                if (have_rows('partners')):
                    while (have_rows('partners')) : the_row();
                        ?>
                        <img class="home-partners__image" src="<?php the_sub_field('partner_image') ?>"
                             alt="Partner Image"/>
                    <?php
                    endwhile;
                endif;
                ?>
            </div>

        </section><!-- #main -->
    </div><!-- #primary -->
<?php

get_sidebar();
get_footer();
