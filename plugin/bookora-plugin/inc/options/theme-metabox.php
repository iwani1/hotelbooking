<?php
/*
 * Theme Metabox
 * @package bookora-tools
 * @since 1.0.0
 * */

if (!defined('ABSPATH')) {
    exit(); // exit if access directly
}

if (class_exists('CSF')) {

    $prefix = 'bookora';

    /*-------------------------------------
    Page Options
    -------------------------------------*/
    $post_metabox = 'bookora_page_meta';

    CSF::createMetabox($post_metabox, array(
        'title' => 'Page Options',
        'post_type' => array('plan', 'page', 'post'),
    ));

    // Header Section
    CSF::createSection($post_metabox, array(
        'title' => 'Header',
        'fields' => array(
            array(
                'type' => 'subheading',
                'content' => esc_html__('Header Option', 'bookora-tools'),
            ),

            array(
                'id' => 'meta_header_type',
                'type' => 'switcher',
                'title' => __('Header Style', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => false
            ),
            array(
                'id' => 'meta_header_style',
                'type' => 'select',
                'title' => __('Select Header Style', 'bookora-plugin'),
                'options' => bookora_Plugin_Helper::get_header_types(),
                'dependency' => array('meta_header_type', '==', 'true'),
            ),
            array(
                'id' => 'page_header_disable',
                'type' => 'switcher',
                'title' => __('DIsable This page Header?', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => false
            ),
        ),
    ));

    CSF::createSection($post_metabox, array(
        'title' => 'Page Breadcrumb',
        'fields' => array(
            array(
                'type' => 'subheading',
                'content' => esc_html__('Page Breadcrumb', 'bookora-tools'),
            ),
            array(
                'id' => 'enable_page_preadcrumb',
                'type' => 'switcher',
                'title' => __('Page Breadcrumb', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => true
            ),
            array(
                'id' => 'hide_bg_img',
                'type' => 'switcher',
                'title' => __('Hide Breadcrumb Page Image', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => true
            ),
            array(
                'id' => 'bg_img_from_page',
                'type' => 'media',
                'title' => esc_html__('Page Breadcrumb Background Image', 'bookora-tools'),
                'dependency' => array('enable_page_preadcrumb', '==', 'true'),

            ),
            array(
                'id' => 'bg_img_page',
                'type' => 'media',
                'title' => esc_html__('Page Breadcrumb Image', 'bookora-tools'),
                'dependency' => array('enable_page_preadcrumb', '==', 'true'),

            ),
            array(
                'id' => 'enable_custom_title',
                'type' => 'switcher',
                'title' => __('Enable Page Custom Title', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => false
            ),

            array(
                'id' => 'page_custom_title',
                'type' => 'text',
                'title' => esc_html__('Page Custom Title', 'bookora-tools'),
                'dependency' => array('enable_custom_title', '==', 'true'),
            ),


        )
    ));

    CSF::createSection($post_metabox, array(
        'title' => 'Page Style',
        'fields' => array(
            array(
                'type' => 'subheading',
                'content' => esc_html__('Page Style', 'bookora-tools'),
            ),
            array(
                'id' => 'select-dark-cls',
                'type' => 'select',
                'title' => 'Body Dark Class',
                'options' => array(
                    '' => 'Select Page Class',
                    'dark-creative-agency' => 'Creative Agency Dark',
                    'dark-business-agency' => 'Business Agency Dark',
                    'dark-modern-agency' => 'Modern Agency Dark',
                    'dark-financial-agency' => 'Financial Agency Dark',
                    'portfolio-agency' => 'Portfolio Agency Dark',
                    'freelancer-agency' => 'Preelancer Agency Dark',
                    'photography-agency' => 'Photography Agency Dark',
                    'interior-agency' => 'Interior Agency Dark'
                ),
            ),
            array(
                'id' => 'select-body-cls',
                'type' => 'select',
                'title' => 'Body Class',
                'options' => array(
                    '' => 'Select Page Class',
                    'agt-home-8' => 'Freelancing Demo Class',
                    'agt-home-1' => 'Agency Home',
                    'agt-modern-ag' => 'Modern Agency Home'
                ),
            ),

            array(
                'id' => 'scroll-bar',
                'type' => 'color',
                'title' => 'Page Scroll Bar Color',
                'output' => 'body::-webkit-scrollbar-thumb',
                'output_mode' => 'background',
            ),
            array(
                'id' => 'scroll-up',
                'type' => 'color',
                'title' => 'Page Scroll UP BUtton Color',
                'output' => '.scroll-top',
                'output_mode' => 'background',
            ),
            array(
                'id' => 'border-up',
                'type' => 'color',
                'title' => 'Breadcrumb Border Color',
                'output' => '.feh-breadcrumb-area',
                'output_mode' => 'border-color',
            ),


        )
    ));


    //Dicrctory 
    $direct_metabox = 'bookora_dicector_meta';

    CSF::createMetabox($direct_metabox, array(
        'title' => 'Directory Options',
        'post_type' => array('at_biz_dir'),
    ));
    CSF::createSection($direct_metabox, array(
        'fields' => array(
            array(
                'type' => 'subheading',
                'content' => esc_html__('Directory Style', 'bookora-tools'),
            ),

            array(
                'id' => 'infos',
                'type' => 'repeater',
                'title' => 'Repeater',
                'fields' => array(

                    array(
                        'id' => 'info-icon',
                        'type' => 'icon',
                        'title' => 'Icon',
                    ),

                    array(
                        'id' => 'info-text',
                        'type' => 'text',
                        'title' => 'Title',
                    ),

                ),

            ),


        )
    ));


    // Header Section
    CSF::createSection($post_metabox, array(
        'title' => 'Footer',
        'fields' => array(
            array(
                'type' => 'subheading',
                'content' => esc_html__('Footer Option', 'bookora-tools'),
            ),

            array(
                'id' => 'meta_footer_type',
                'type' => 'switcher',
                'title' => __('Footer Style', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => false
            ),
            array(
                'id' => 'meta_footer_style',
                'type' => 'select',
                'title' => __('Select Footer Style', 'bookora-plugin'),
                'options' => bookora_Plugin_Helper::get_footer_types(),
                'dependency' => array('meta_footer_type', '==', 'true'),
            ),
            array(
                'id' => 'page_footer_disable',
                'type' => 'switcher',
                'title' => __('DIsable This page Footer?', 'bookora-plugin'),
                'text_on' => __('Yes', 'bookora-plugin'),
                'text_off' => __('No', 'bookora-plugin'),
                'default' => false
            ),

        ),
    ));

    /*-------------------------------------
   Page Options
   -------------------------------------*/
    $post_metabox = 'bookora_pricing_meta';

    CSF::createMetabox($post_metabox, array(
        'title' => 'Pricing Options',
        'post_type' => 'bookora_pricing',
    ));

    // Header Section
    CSF::createSection($post_metabox, array(
        'title' => 'bookora Pricing Table ',
        'fields' => array(
            array(
                'id' => 'populer_item',
                'type' => 'checkbox',
                'title' => 'Select Populer Item',
                'label' => 'If you want to Populer Item then please check the box',
                'default' => false // or false
            ),
            array(
                'id' => 'offer_text',
                'type' => 'text',
                'title' => esc_html__('Offer Text', 'bookora-tools'),
            ),
            array(
                'id' => 'currency',
                'type' => 'text',
                'title' => esc_html__('Currency', 'bookora-tools'),
                'default' => '$',
            ),
            array(
                'id' => 'monthly_price',
                'type' => 'text',
                'title' => esc_html__('Monthly Price ', 'bookora-tools'),
            ),
            array(
                'id' => 'yearly_price',
                'type' => 'text',
                'title' => esc_html__('Yearly Price ', 'bookora-tools'),
            ),
            array(
                'id' => 'mon_period',
                'type' => 'text',
                'title' => esc_html__('Monthly Period ', 'bookora-tools'),
                'default' => esc_html__('/ Monthly', 'bookora-tools'),
            ),
            array(
                'id' => 'yr_period',
                'type' => 'text',
                'title' => esc_html__('Yearly Period ', 'bookora-tools'),
                'default' => esc_html__('/ Yearly', 'bookora-tools'),
            ),
            array(
                'id' => 'pricing_lists',
                'type' => 'group',
                'title' => 'Add Pricing List Item',
                'fields' => array(

                    array(
                        'id' => 'list-item',
                        'type' => 'text',
                        'title' => esc_html__('Pricing List Item', 'bookora-tools'),
                    ),
                    array(
                        'id' => 'exclude-in-package',
                        'title' => esc_html__('Exclude In This Package', 'bookora-tools'),
                        'type' => 'switcher',
                        'default' => false,
                    ),
                )

            ),
            array(
                'id' => 'pricing_btn',
                'type' => 'link',
                'title' => esc_html__('Pricing Button', 'bookora-tools'),
            ),
            array(
                'id' => 'pricing_txt',
                'type' => 'text',
                'title' => esc_html__('Pricing Text', 'bookora-tools'),
            ),

        ),
    ));

    /*-------------------------------------
    Page Options
    -------------------------------------*/
    $post_metabox = 'bookora_career_meta';

    CSF::createMetabox($post_metabox, array(
        'title' => 'Career Options',
        'post_type' => 'bookora_career',
    ));

    // Header Section
    CSF::createSection($post_metabox, array(
        'fields' => array(

            array(
                'id' => 'icon',
                'type' => 'icon',
                'title' => esc_html__('Job Icon', 'bookora-tools'),
            ),

            array(
                'id' => 'job_type',
                'type' => 'text',
                'title' => esc_html__('Job Type', 'bookora-tools'),
                'desc' => esc_html__('Job Type Means Full Time Job or Part Time Job', 'bookora-tools'),
            ),
            array(
                'id' => 'job_location',
                'type' => 'text',
                'title' => esc_html__('Job Location', 'bookora-tools'),
                'desc' => esc_html__('Type Yor Job Location', 'bookora-tools'),
            ),
            array(
                'id' => 'job_date',
                'type' => 'date',
                'title' => esc_html__('Job Date', 'bookora-tools'),
                'desc' => esc_html__('Type Yor Job Ending Date Here', 'bookora-tools'),
            ),
            array(
                'id' => 'job_salary',
                'type' => 'text',
                'title' => esc_html__('Job Salary', 'bookora-tools'),
                'desc' => esc_html__('Type Yor Job Salary Range Here', 'bookora-tools'),
            ),
            array(
                'id' => 'job_excerpt',
                'type' => 'textarea',
                'title' => esc_html__('Job Excerpt', 'bookora-tools'),
                'desc' => esc_html__('Type Yor Job Short Description Here', 'bookora-tools'),
            ),
        ),
    ));



    /*-------------------------------------
    Page Options
    -------------------------------------*/
    $bookora_temp_meta = 'bookora_temp_meta';

    CSF::createMetabox($bookora_temp_meta, array(
        'title' => 'Template Type',
        'post_type' => array('bookora_template'),
        'data_type' => 'unserialize'
    ));

    // Header Section
    CSF::createSection($bookora_temp_meta, array(
        'fields' => array(
            array(
                'id' => 'bookora_template_type',
                'type' => 'select',
                'title' => 'Select Template Type',
                'placeholder' => 'Select Template Type',
                'options' => array(
                    'tf_header_key' => 'Header',
                    'tf_footer_key' => 'Footer',
                ),
                'default' => ''
            ),
        ),
    ));


    /*-------------------------------------
    Portfolio Options
    -------------------------------------*/
    $bookora_port_meta = 'bookora_portfolio_meta';

    CSF::createMetabox($bookora_port_meta, array(
        'title' => 'Portfolio Option',
        'post_type' => array('bookora_portfolio'),
    ));

    // Header Section
    CSF::createSection($bookora_port_meta, array(
        'fields' => array(
            array(
                'id' => 'port-column',
                'type' => 'select',
                'title' => 'Select Portfolio Column',
                'options' => array(
                    '12' => 'Full Column',
                    '6' => 'Two Column',
                    '3' => 'Four Column',
                    '4' => 'Three Column',
                ),
                'default' => '12'
            ),
            array(
                'id' => 'cl_no',
                'type' => 'text',
                'title' => esc_html__('Job Salary', 'bookora-tools'),
                'desc' => esc_html__('Type Yor Job Salary Range Here', 'bookora-tools'),
            ),
        ),
    ));


    /*-------------------------------------
    Post Format Options
    -------------------------------------*/
    $post_format_metabox = 'bookora_post_format_meta';

    CSF::createMetabox($post_format_metabox, array(
        'title' => 'Post Video',
        'post_type' => 'post',
        'post_formats' => 'video',
        'data_type' => 'serialize',
        'context' => 'advanced',
        'priority' => 'default',
    ));

    // Video Link
    CSF::createSection($post_format_metabox, array(
        'fields' => array(
            array(
                'id' => 'video_link',
                'type' => 'text',
                'title' => esc_html__('Video Link', 'bookora-plugin'),
                'desc' => esc_html__('Enter Video Link Here', 'bookora-plugin'),
            ),
        ),
    ));
    /*-------------------------------------
    Post Audio Options
    -------------------------------------*/
    $post_audio_metabox = 'post_audio_metabox';

    CSF::createMetabox($post_audio_metabox, array(
        'title' => 'Post Audio',
        'post_type' => 'post',
        'post_formats' => 'audio',
        'data_type' => 'serialize',
        'context' => 'advanced',
        'priority' => 'default',
    ));

    // Video Link
    CSF::createSection($post_audio_metabox, array(
        'fields' => array(
            array(
                'id' => 'audio_link',
                'type' => 'text',
                'title' => esc_html__('Audio Link', 'bookora-plugin'),
                'desc' => esc_html__('Enter Audio Link Here', 'bookora-plugin'),
            ),
        ),
    ));

    /**
     * Post Gallery Format
     */
    $post_format_gall_metabox = 'bookora_post_gall_meta';

    CSF::createMetabox($post_format_gall_metabox, array(
        'title' => 'Post Gallery',
        'post_type' => 'post',
        'post_formats' => 'gallery',
        'data_type' => 'serialize',
        'context' => 'advanced',
        'priority' => 'default',
    ));

    // Video Link

    CSF::createSection($post_format_gall_metabox, array(
        'fields' => array(
            array(
                'id' => 'post-gall-item',
                'type' => 'gallery',
                'title' => 'Gallery',
                'add_title' => 'Add Images',
                'edit_title' => 'Edit Images',
                'clear_title' => 'Remove Images',
            ),
        ),
    ));





} //endif
