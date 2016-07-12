<?php
/**
 * Register Visual Composer shortcodes here
 *
 * @link https://wpbakery.atlassian.net/wiki/display/VC/Visual+Composer+Pagebuilder+for+WordPress
 *
 * @package Beetroot
 */
// Check if Visual Composer is installed
if ( defined( 'WPB_VC_VERSION' ) ) {
// Testimonials Slider Wrapper
    class WPBakeryShortCode_testimonial_slider extends WPBakeryShortCodesContainer
    {
    }

    add_action('vc_before_init', 'testimonial_slider');
    function testimonial_slider()
    {
        vc_map(array(
            'name' => __('Testimonial slider', 'beetroot'),
            'base' => 'testimonial_slider',
            'class' => 'testimonial-slider',
            'category' => esc_html__('Beetroot', 'beetroot'),
            'description' => esc_html__('Testimonials Slider', 'beetroot'),
            'as_parent' => array('only' => 'testimonial_item'),
            'admin_enqueue_js' => array(get_template_directory_uri() . '../assets/javascript/global.js',
            ),
            'icon' => array(get_template_directory_uri() . '/images/icon-testimonial_slider.png'),
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'class' => 'testimonial_control_pos',
                    'heading' => __('Controls Position', 'beetroot'),
                    'param_name' => 'testimonial_slider_position',
                    'value' => array(
                        __('Top', 'beetroot') => 'top_controls',
                        __('Bottom', 'beetroot') => 'bottom_controls',
                        __('Left and Right', 'beetroot') => 'lnr_controls',
                    ),
                    'save_always' => true,
                    'description' => __('Select slider controls position', 'beetroot'),
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Extra Class Name', 'beetroot'),
                    'param_name' => 'el_class',
                    'value' => __('', 'beetroot'),
                    'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'beetroot'),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __('Css', 'beetroot'),
                    'param_name' => 'css',
                    'group' => __('Design options', 'beetroot'),
                ),
            ),
            'js_view' => 'VcColumnView',
        ));
    }

    ;

// Testimonials Slider Item
    class WPBakeryShortCode_testimonial_item extends WPBakeryShortCode
    {
    }

    add_action('vc_before_init', 'vc_map_testimonial_item');
    function vc_map_testimonial_item()
    {
        vc_map(array(
            'name' => esc_html__('Testimonial Slider Item', 'beetroot'),
            'base' => 'testimonial_item',
            'category' => esc_html__('Beetroot', 'beetroot'),
            'description' => esc_html__('Testimonial Slider Item with avatar', 'beetroot'),
            'as_child' => array('only' => 'testimonial_slider'),
            'content_element' => true,
            'icon' => get_template_directory_uri() . '/images/icon-testimonial_item.png',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Name', 'beetroot'),
                    'param_name' => 'name',
                    'value' => '',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => esc_html__('Title', 'beetroot'),
                    'param_name' => 'company',
                    'value' => '',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__('Avatar', 'beetroot'),
                    'param_name' => 'avatar',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea_html',
                    'heading' => esc_html__('Testimonial Content', 'beetroot'),
                    'param_name' => 'content',
                    'value' => '',
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Extra Class Name', 'beetroot'),
                    'param_name' => 'el_class',
                    'value' => __('', 'beetroot'),
                    'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'beetroot'),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __('Css', 'beetroot'),
                    'param_name' => 'css',
                    'group' => __('Design options', 'beetroot'),
                ),
            ),
        ));
    }

// List item with icon
    class WPBakeryShortCode_icon_list_item extends WPBakeryShortCode
    {
    }

    add_action('vc_before_init', 'vc_map_icon_list_item');
    function vc_map_icon_list_item()
    {
        vc_map(array(
            'name' => esc_html__('Icon List Item', 'beetroot'),
            'base' => 'icon_list_item',
            'category' => esc_html__('Beetroot', 'beetroot'),
            'icon' => get_template_directory_uri() . '/images/icon-icon_list_item.png',
            'params' => array_merge(
                array(
                    array(
                        'type' => 'textarea_html',
                        'heading' => esc_html__('Text', 'beetroot'),
                        'param_name' => 'content',
                        'value' => esc_html__('List Item Text', 'beetroot'),
                        'admin_label' => true,
                    ),
                ),
                array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Font Size', 'beetroot'),
                        'param_name' => 'font_size',
                        'value' => '',
                        'description' => esc_html__('Enter font size.', 'beetroot'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Icon Border Width', 'beetroot'),
                        'param_name' => 'border_width',
                        'value' => '',
                        'description' => esc_html__('Enter icon border width.', 'beetroot'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Font Weight', 'beetroot'),
                        'param_name' => 'font_weight',
                        'value' => array(
                            __('100', 'beetroot') => '100',
                            __('200', 'beetroot') => '200',
                            __('300', 'beetroot') => '300',
                            __('400', 'beetroot') => '400',
                            __('500', 'beetroot') => '500',
                            __('600', 'beetroot') => '600',
                            __('700', 'beetroot') => '700',
                        ),
                        'description' => esc_html__('Select font weight.', 'beetroot'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Icon Border Style', 'beetroot'),
                        'param_name' => 'border_style',
                        'value' => array(
                            __('Solid', 'beetroot') => 'solid',
                            __('Dashed', 'beetroot') => 'dashed',
                            __('Dotted', 'beetroot') => 'dotted',
                            __('Double', 'beetroot') => 'double',
                        ),
                        'description' => esc_html__('Select font weight.', 'beetroot'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => esc_html__('Text Color', 'beetroot'),
                        'param_name' => 'text_color',
                        'value' => '',
                        'description' => esc_html__('Select text color. Leave blank to use default text color.', 'beetroot'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => esc_html__('Icon Color', 'beetroot'),
                        'param_name' => 'icon_color',
                        'value' => '',
                        'description' => esc_html__('Select icon color. Leave blank to use primary color.', 'beetroot'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Icon library', 'js_composer'),
                        'value' => array(
                            __('Font Awesome', 'js_composer') => 'fontawesome',
                            __('Open Iconic', 'js_composer') => 'openiconic',
                            __('Typicons', 'js_composer') => 'typicons',
                            __('Entypo', 'js_composer') => 'entypo',
                            __('Linecons', 'js_composer') => 'linecons',
                            __('Mono Social', 'js_composer') => 'monosocial',
                        ),
                        'admin_label' => true,
                        'param_name' => 'type',
                        'description' => __('Select icon library.', 'js_composer'),
                    ),
                    array(
                        'type' => 'iconpicker',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'icon_fontawesome',
                        'value' => 'fa fa-adjust', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => false,
                            // default true, display an "EMPTY" icon?
                            'iconsPerPage' => 4000,
                            // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                        ),
                        'dependency' => array(
                            'element' => 'type',
                            'value' => 'fontawesome',
                        ),
                        'description' => __('Select icon from library.', 'js_composer'),
                    ),
                    array(
                        'type' => 'iconpicker',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'icon_openiconic',
                        'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => false, // default true, display an "EMPTY" icon?
                            'type' => 'openiconic',
                            'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                        ),
                        'dependency' => array(
                            'element' => 'type',
                            'value' => 'openiconic',
                        ),
                        'description' => __('Select icon from library.', 'js_composer'),
                    ),
                    array(
                        'type' => 'iconpicker',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'icon_typicons',
                        'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => false, // default true, display an "EMPTY" icon?
                            'type' => 'typicons',
                            'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                        ),
                        'dependency' => array(
                            'element' => 'type',
                            'value' => 'typicons',
                        ),
                        'description' => __('Select icon from library.', 'js_composer'),
                    ),
                    array(
                        'type' => 'iconpicker',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'icon_entypo',
                        'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => false, // default true, display an "EMPTY" icon?
                            'type' => 'entypo',
                            'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                        ),
                        'dependency' => array(
                            'element' => 'type',
                            'value' => 'entypo',
                        ),
                    ),
                    array(
                        'type' => 'iconpicker',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'icon_linecons',
                        'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => false, // default true, display an "EMPTY" icon?
                            'type' => 'linecons',
                            'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                        ),
                        'dependency' => array(
                            'element' => 'type',
                            'value' => 'linecons',
                        ),
                        'description' => __('Select icon from library.', 'js_composer'),
                    ),
                    array(
                        'type' => 'iconpicker',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'icon_monosocial',
                        'value' => 'vc-mono vc-mono-fivehundredpx', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => false, // default true, display an "EMPTY" icon?
                            'type' => 'monosocial',
                            'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                        ),
                        'dependency' => array(
                            'element' => 'type',
                            'value' => 'monosocial',
                        ),
                        'description' => __('Select icon from library.', 'js_composer'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Icon color', 'js_composer'),
                        'param_name' => 'color',
                        'value' => array_merge(getVcShared('colors'), array(__('Custom color', 'js_composer') => 'custom')),
                        'description' => __('Select icon color.', 'js_composer'),
                        'param_holder_class' => 'vc_colored-dropdown',
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Custom color', 'js_composer'),
                        'param_name' => 'custom_color',
                        'description' => __('Select custom icon color.', 'js_composer'),
                        'dependency' => array(
                            'element' => 'color',
                            'value' => 'custom',
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Background shape', 'js_composer'),
                        'param_name' => 'background_style',
                        'value' => array(
                            __('None', 'js_composer') => '',
                            __('Circle', 'js_composer') => 'rounded',
                            __('Square', 'js_composer') => 'boxed',
                            __('Rounded', 'js_composer') => 'rounded-less',
                            __('Outline Circle', 'js_composer') => 'rounded-outline',
                            __('Outline Square', 'js_composer') => 'boxed-outline',
                            __('Outline Rounded', 'js_composer') => 'rounded-less-outline',
                        ),
                        'description' => __('Select background shape and style for icon.', 'js_composer'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Background color', 'js_composer'),
                        'param_name' => 'background_color',
                        'value' => array_merge(getVcShared('colors'), array(__('Custom color', 'js_composer') => 'custom')),
                        'std' => 'grey',
                        'description' => __('Select background color for icon.', 'js_composer'),
                        'param_holder_class' => 'vc_colored-dropdown',
                        'dependency' => array(
                            'element' => 'background_style',
                            'not_empty' => true,
                        ),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Custom background color', 'js_composer'),
                        'param_name' => 'custom_background_color',
                        'description' => __('Select custom icon background color.', 'js_composer'),
                        'dependency' => array(
                            'element' => 'background_color',
                            'value' => 'custom',
                        ),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Icon Border Color', 'js_composer'),
                        'param_name' => 'icon_border_color',
                        'description' => __('Select icon border color.', 'js_composer'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Size', 'js_composer'),
                        'param_name' => 'size',
                        'value' => array_merge(getVcShared('sizes'), array('Extra Large' => 'xl', 'No Padding Mini' => 'no_padding_mini')),
                        'std' => 'md',
                        'description' => __('Icon size.', 'js_composer'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Icon alignment', 'js_composer'),
                        'param_name' => 'align',
                        'value' => array(
                            __('Left', 'js_composer') => 'left',
                            __('Right', 'js_composer') => 'right',
                        ),
                        'description' => __('Select icon alignment.', 'js_composer'),
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => __('URL (Link)', 'js_composer'),
                        'param_name' => 'link',
                        'description' => __('Add link to icon.', 'js_composer'),
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra Class Name', 'beetroot'),
                        'param_name' => 'el_class',
                        'value' => __('', 'beetroot'),
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'beetroot'),
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __('Css', 'beetroot'),
                        'param_name' => 'css',
                        'group' => __('Design options', 'beetroot'),
                    ),
                )
            ),
        ));
    }
// END VC Check
}