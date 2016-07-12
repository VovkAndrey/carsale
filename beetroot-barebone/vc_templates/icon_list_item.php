<?php
$type = $icon_fontawesome = $icon_openiconic = $icon_typicons =
$icon_entypo = $icon_linecons = $color = $custom_color =
$background_style = $background_color = $custom_background_color =
$size = $align = $el_class = $link = $css_animation = $css = '';

$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);

// Construct Icon Class
$iconClass = isset(${'icon_'.$type}) ? esc_attr(${'icon_'.$type}) : 'fa fa-adjust';

// Construct Text CSS
$text_css = '';
// Add font size if its set
if (!empty($font_size)) {
    $text_css .= 'font-size:'.$font_size.';';
}
// Add font weight if its set
if (!empty($font_weight)) {
    $text_css .= 'font-weight:'.$font_weight.';';
}
// Add text color if its set
if (!empty($text_color)) {
    $text_css .= 'color:'.$text_color.';';
}
// Construct Icon CSS
$icon_css = '';
// Add icon custom color if its set
if (!empty($custom_color)) {
    $icon_css .= 'color:'.$custom_color.';';
}
// Add icon background color if its set
if (!empty($background_color) && empty($custom_background_color) && !empty($background_style)) {
    $icon_css .= 'background-color:'.$background_color.';';
}
// Add icon background color if its set
if (!empty($custom_background_color)) {
    $icon_css .= 'background-color:'.$custom_background_color.';';
}
// Use icon color if custom color is not set
if (empty($custom_color)) {
    $icon_color = $color;
}
// Add border-width if set
if(!empty($border_width)){
    $icon_css .= 'border-width:' . $border_width . ';';
}
else {
    $icon_css .= 'border-width: 0;';
}
// Add border-style if set
if(!empty($border_style)){
    $icon_css .= 'border-style:' . $border_style . ';';
}
// Add border-color if set
if(!empty($icon_border_color)){
    $icon_css .= 'border-color:' . $icon_border_color . ';';
}
// Build link
$url = vc_build_link($link);

// Check if sizes are set and construct them
$has_style = false;
if (strlen($background_style) > 0) {
    $has_style = true;
    if (strpos($background_style, 'outline') !== false) {
        $background_style .= ' vc_icon_element-outline'; // if we use outline style it is border in css
    } else {
        $background_style .= ' vc_icon_element-background';
    }
}
?>
<?php
//Construct Icon HTML
$icon_html = '<span class="icon-list-item__icon-wrap"><i class="'.$iconClass.' '.'vc_icon_element-size-'.esc_attr($size).' '.'vc_icon_element-color-'.$icon_color.' '.'vc_icon_element-style-'.esc_attr($background_style).'"'.'style="'. $icon_css .'"'.'></i></span>';
 ?>


<div class="icon-list-item <?php echo $el_class .' '. esc_attr($css_class);?>" >
	<?php // Wrap elements in <a> element if link is set
    if (strlen($link) > 0 && strlen($url['url']) > 0) {
    echo '<a class="vc_icon_element-link" href="'.esc_attr($url['url']).'" title="'.esc_attr($url['title']).'" target="'.(strlen($url['target']) > 0 ? esc_attr($url['target']) : '_self').'">';
}        // Align icon to left if set
        if ($align == 'left') { echo $icon_html; } ?>
        <span class="icon-list-item__text"<?php if (!empty($text_css)) { echo 'style="'.$text_css.'"'; } ?>><?php echo $content; ?></span>
        <?php /* Align icon to right if set */ if ($align == 'right') { echo $icon_html; } if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) { echo '</a>'; } ?>
</div><?php echo $this->endBlockComment('icon_list_item'); ?>
