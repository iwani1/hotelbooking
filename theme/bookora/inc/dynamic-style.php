<?php

// File Security Check
if (!defined('ABSPATH')) {
    exit;
}

function bookora_Theme_options_style()
{

    //
    // Enqueueing StyleSheet file
    //
    wp_enqueue_style('bookora-theme-custom-style', get_template_directory_uri() . '/assets/css/custom-style.css');
    $css_output = '';
    $thx_color_1 = cs_get_option('theme-color-1');
    $thx_color_2 = cs_get_option('theme-color-2');
    $thx_color_3 = cs_get_option('theme-color-3');
    $thx_color_4 = cs_get_option('theme-color-4');

    /**
     * Theme Color 1
     */

    if (!empty($thx_color_1)) {
        $css_output .= '       
        :root {
            --tc-prime-c: ' . esc_attr($thx_color_1) . ';
        }
        ';
    }
    if (!empty($thx_color_2)) {
        $css_output .= '       
        :root {
            --tc-prime-c2: ' . esc_attr($thx_color_2) . ';
        }
        ';
    }
    if (!empty($thx_color_3)) {
        $css_output .= '       
        :root {
            --tc-prime-c3: ' . esc_attr($thx_color_3) . ';
        }
        ';
    }
    if (!empty($thx_color_4)) {
        $css_output .= '       
        :root {
            --tc-prime-c4: ' . esc_attr($thx_color_4) . ';
        }
        ';
    }

    wp_add_inline_style('bookora-theme-custom-style', $css_output);

}
add_action('wp_enqueue_scripts', 'bookora_Theme_options_style');
