<?php
/*
 * Theme Options
 * @package bookora
 * @since 1.0.0
 * */

if (!defined('ABSPATH')) {
    exit(); // exit if access directly
}

if (class_exists('CSF')) {

    //
    // Set a unique slug-like ID
    $prefix = 'bookora';

    //
    // Create options
    CSF::createOptions($prefix . '_theme_options', array(
        'menu_title' => 'bookora Option',
        'menu_slug' => 'bookora-theme-option',
        'menu_type' => 'menu',
        'enqueue_webfont' => true,
        'show_in_customizer' => true,
        'menu_icon' => 'dashicons-category',
        'menu_position' => 50,
        'theme' => 'dark',
        'framework_title' => wp_kses_post('Haptic Options <small>by Raziul <br/> Version: 1.0</small> '),
        'footer_text' => wp_kses_post('The Theme will Created By Themexriver '),
    ));

    // Create a top-tab
    CSF::createSection($prefix . '_theme_options', array(
        'id' => 'header_opts', // Set a unique slug-like ID
        'title' => 'Header',
    ));


    /*-------------------------------------------------------
     ** Logo Settings  Options
    --------------------------------------------------------*/

    /*-------------------------------------------------------
     ** Header  Options
    --------------------------------------------------------*/

    CSF::createSection($prefix . '_theme_options', array(
        'title' => 'General Settings',
        'id' => 'general_settings',
        'icon' => 'fa fa-refresh',
        'fields' => array(

            array(
                'id' => 'preloader_enable',
                'title' => esc_html__('Enable Preloader', 'bookora-tools'),
                'type' => 'switcher',
                'desc' => esc_html__('Enable or Disable Preloader', 'bookora-tools'),
                'default' => true,
            ),

            array(
                'id' => 'preloader_logo',
                'type' => 'media',
                'title' => esc_html__('Preloader Logo Upload', 'bookora-tools'),
            ),

            array(
                'id' => 'scroll_up_btn',
                'title' => esc_html__('Scroll Up SHOW/HIDE', 'bookora-tools'),
                'type' => 'switcher',
                'desc' => esc_html__('Enable or Disable Scroll UP', 'bookora-tools'),
                'default' => true,
            ),

        ),
    ));

    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Header', 'bookora-tools'),
        'parent' => 'header_opts',
        'icon' => 'fa fa-header',
        'fields' => array(
            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Header Layout', 'bookora-tools') . '</h3>',
            ),

            array(
                'id' => 'header_style',
                'type' => 'select',
                'title' => __('Select Header Style', 'bookora-tools'),
                'options' => bookora_Plugin_Helper::get_header_types(),
                'desc' => sprintf(
                    __('%sClick%s to create New Header', 'bookora-tools'),
                    '<a href="' . admin_url('edit.php?post_type=bookora_template') . '" target="_blank">',
                    '</a>'
                ),
            )

        ),
    ));


    /*-------------------------------------
     ** Typography Options
    -------------------------------------*/
    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Typography', 'bookora-tools'),
        'id' => 'typography_options',
        'icon' => 'fa fa-font',
        'fields' => array(

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Body', 'bookora-tools') . '</h3>',
            ),

            array(
                'id' => 'body-typography',
                'type' => 'typography',
                'output' => 'body',

            ),

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading', 'bookora-tools') . '</h3>',
            ),

            array(
                'id' => 'heading-gl-typo',
                'type' => 'typography',
                'output' => 'h1, h2, h3, h4, h5, h6',
            ),

        ),
    ));

    // blog optoins
    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Blog', 'bookora-tools'),
        'id' => 'blog_page',
        'icon' => 'fa fa-rss-square',
        'fields' => array(

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Blog Options', 'bookora-tools') . '</h3>',
            ),

            array(
                'id' => 'breadcrumb_bg',
                'type' => 'media',
                'title' => esc_html__('Single Breadcrumb BG', 'bookora-tools'),
            ),

            array(
                'id' => 'blog_btn_text',
                'type' => 'text',
                'title' => esc_html__('Blog Read More Button', 'bookora-tools'),
                'default' => esc_html__('Explore More', 'bookora-tools'),
                'desc' => esc_html__('Type Blog Read More Button Text Here', 'bookora-tools'),
            ),
        ),
    ));
    // blog optoins
    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Breadcrumb', 'bookora-tools'),
        'id' => 'breadcrumb_settings',
        'icon' => 'fa fa-rss-square',
        'fields' => array(
            array(
                'id' => 'shape-1',
                'type' => 'media',
                'title' => esc_html__('Breadcrumb Shape. 1', 'bookora-tools'),
            ),
            array(
                'id' => 'shape-2',
                'type' => 'media',
                'title' => esc_html__('Breadcrumb Shape. 2', 'bookora-tools'),
            ),
            array(
                'id' => 'shape-3',
                'type' => 'media',
                'title' => esc_html__('Breadcrumb Shape. 3', 'bookora-tools'),
            ),
            array(
                'id' => 'shape-4',
                'type' => 'media',
                'title' => esc_html__('Breadcrumb Shape. 4', 'bookora-tools'),
            ),

            array(
                'id' => 'breadcrumb_tag_link',
                'type' => 'text',
                'title' => esc_html__('Breadcrumb Tagline', 'bookora-tools'),
                'default' => esc_html__('bookora.', 'bookora-tools'),
            ),
        ),
    ));


    // bookora Color Setting
    CSF::createSection($prefix . '_theme_options', array(
        'title' => 'Color Control',
        'id' => 'apix_color_control',
        'icon' => 'fa fa-paint-brush',
        'fields' => array(


            array(  //nav bar one start
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Theme Global Color', 'bookora-tools') . '</h3>',
            ),
            array(
                'id' => 'theme-color-1',
                'type' => 'color',
                'title' => 'Theme Color One',
                'default' => '#135CFD'
            ),
            array(
                'id' => 'theme-color-2',
                'type' => 'color',
                'title' => 'Theme Color Two',
                'default' => '#AD4BFF'
            ),
            array(
                'id' => 'theme-color-3',
                'type' => 'color',
                'title' => 'Theme Color Three',
                'default' => '#5550FF'
            ),
            array(
                'id' => 'theme-color-4',
                'type' => 'color',
                'title' => 'Theme Color Four',
                'default' => '#3CEDB7'
            )

        ),
    ));

    // Create a section
    CSF::createSection($prefix . '_theme_options', array(
        'title' => 'Error Page',
        'id' => 'error_page',
        'icon' => 'fa fa-exclamation-triangle',
        'fields' => array(

            array(  //nav bar one start
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('404 Page Options', 'bookora-tools') . '</h3>',
            ),

            array(
                'id' => 'error_img',
                'type' => 'media',
                'title' => esc_html__('404 Image', 'bookora-tools')
            ),

            array(
                'id' => 'error_title',
                'type' => 'text',
                'title' => esc_html__('404 Title', 'bookora-tools'),
                'default' => esc_html__('Oops!', 'bookora-tools'),
            ),
            array(
                'id' => 'error_code',
                'type' => 'text',
                'title' => esc_html__('404 Code', 'bookora-tools'),
                'default' => esc_html__('404 Error!', 'bookora-tools'),
            ),
            array(
                'id' => 'error_button',
                'type' => 'text',
                'title' => esc_html__('404 Button', 'bookora-tools'),
                'default' => esc_html__('back to Home page ', 'bookora-tools'),
            )


        ),
    ));


    /*-------------------------------------------------------
     ** Footer  Options
    --------------------------------------------------------*/

    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Footer Options', 'bookora-tools'),
        'icon' => 'fa fa-copyright',
        'fields' => array(

            array(
                'id' => 'footer_style',
                'type' => 'select',
                'title' => __('Select Footer Style', 'bookora-tools'),
                'desc' => sprintf(
                    __('%sClick%s to create New Footer', 'bookora-tools'),
                    '<a href="' . admin_url('edit.php?post_type=bookora_template') . '" target="_blank">',
                    '</a>'
                ),
                'options' => bookora_Plugin_Helper::get_footer_types(),
            ),


        ),
    ));

    // Backup section
    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Backup Export', 'bookora-tools'),
        'id' => 'backup_options',
        'icon' => 'fa fa-window-restore',
        'fields' => array(
            array(
                'type' => 'backup',
            ),
        ),
    ));




}