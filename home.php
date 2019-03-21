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

                show_car_block($args);
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
