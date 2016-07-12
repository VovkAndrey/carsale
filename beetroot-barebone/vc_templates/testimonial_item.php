<?php
$css = '';
extract( shortcode_atts( array(
	'name' => '',
	'avatar' => '',
	'company' => '',
	'el_class' => '',
	'css' => ''
), $atts ) );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>
		<div class="testimonials-item-wrap <?php echo $el_class . '' . esc_attr( $css_class ); ?>">
			<div class="testimonials-item-photo">
				<img src="<?php echo wp_get_attachment_url($avatar); ?>" alt="Testimonial Photo" class="testimonials-avatar">
			</div>
			<blockquote class="testimonials-quote"><?php echo $content; ?></blockquote>
			<section class="testimonial-author">
				<span class="testimonial-name"><?php echo $name; ?></span>
				<span class="testimonial-company"><?php echo $company; ?></span>
			</section>

		</div><?php echo $this->endBlockComment('testimonials_item'); ?>
