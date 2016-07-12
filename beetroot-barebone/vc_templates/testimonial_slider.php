<?php
$css = '';
extract( shortcode_atts( array(
	'testimonial_slider_position' => '',
	'el_class' => '',
	'css' => ''
), $atts ) );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>
	<div class="testimonials-container <?php echo $testimonial_slider_position . '' . $el_class . '' . esc_attr( $css_class ); ?>">
			<?php echo do_shortcode($content); ?>
	</div><?php echo $this->endBlockComment('testimonials_slider'); ?>
