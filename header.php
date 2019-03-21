<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="banner">
	<div class="container">
		<a class="brand" href="<?php echo esc_url(home_url('/')); ?>"><?php echo wp_get_attachment_image(get_field('logo', 'option'), array(100,100)); ?></a>
		<nav class="nav-primary">
			<?php
			if (has_nav_menu('primary')) :
				wp_nav_menu(['theme_location' => 'primary', 'menu_id' => 'primary-menu', 'walker' => new carsale_navwalker()]);
			endif;
			?>
		</nav><!-- .nav-primary -->
	</div><!-- .container -->
</header><!-- .banner -->
<div id="content" class="site-content">