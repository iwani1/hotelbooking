<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */
namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.
class Bookora_Hero extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve Elementor widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'bookora-hero';
    }

    /**
     * Get widget title.
     *
     * Retrieve Elementor widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Hero', 'bookora-plugin');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Elementor widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'bookora-custom-icon';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Elementor widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['bookora_widgets'];
    }


    protected function register_controls()
    {

        $this->start_controls_section(
            'int_widget_opt',
            [
                'label' => esc_html__('Hero Style Select', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__('Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'bookora-plugin'),
                    '2' => esc_html__('Style 2', 'bookora-plugin'),
                    '3' => esc_html__('Style 3', 'bookora-plugin')
                ]
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Sub Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'bookora-plugin'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => __('H1', 'bookora-plugin'),
                        'icon' => 'eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => __('H2', 'bookora-plugin'),
                        'icon' => 'eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => __('H3', 'bookora-plugin'),
                        'icon' => 'eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => __('H4', 'bookora-plugin'),
                        'icon' => 'eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => __('H5', 'bookora-plugin'),
                        'icon' => 'eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => __('H6', 'bookora-plugin'),
                        'icon' => 'eicon-editor-h6',
                    ],
                ],
                'default' => 'h1',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'default' => esc_html__('Button Label', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label' => esc_html__('Button Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'btn_label2',
            [
                'label' => esc_html__('Button Label 2', 'bookora-plugin'),
                'default' => esc_html__('Button Label', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'btn_link2',
            [
                'label' => esc_html__('Button Link 2', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'video_link',
            [
                'label' => esc_html__('Video Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $this->add_control(
            'video_text',
            [
                'label' => esc_html__('Video Text', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );

        $this->add_control(
            'search_hide',
            [
                'label' => esc_html__('Advance Search Hide', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'bookora-plugin'),
                'label_off' => esc_html__('Hide', 'bookora-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );
        $this->add_control(
            'animation_hide',
            [
                'label' => esc_html__('Line Animation Hide', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'bookora-plugin'),
                'label_off' => esc_html__('Hide', 'bookora-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $this->add_control(
            'video_type',
            [
                'label' => __('Video Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'exu' => [
                        'title' => __('External URL', 'bookora-core'),
                        'icon' => 'eicon-slider-video',
                    ],
                    'slfu' => [
                        'title' => __('Self Upload', 'bookora-core'),
                        'icon' => 'eicon-video-camera',
                    ],
                ],
                'default' => 'exu',
                'toggle' => false,
                'style_transfer' => true,
                'condition' => [
                    'style' => '2',
                ],
            ]
        );

        $this->add_control(
            'video_links',
            [
                'label' => esc_html__('Video Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'video_type' => 'exu',
                    'style' => '2',
                ],
            ]
        );
        $this->add_control(
            'video_upload',
            [
                'label' => esc_html__('Video Upload', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'media_types' => ['video'],
                'condition' => [
                    'video_type' => 'slfu',
                    'style' => '2',
                ],
            ]
        );
        $this->add_control(
            'search_title',
            [
                'label' => esc_html__('Search Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['3'],
                    'search_hide' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'booking_shortcode',
            [
                'label' => esc_html__('Booking provider shortcode', 'bookora-plugin'),
                'description' => esc_html__('Install and configure a booking provider first. Its shortcode renders the real availability form; this theme does not provide reservations.', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['3'],
                    'search_hide' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'loc_cate',
            [
                'label' => esc_html__('Locations', 'bookora-plugin'),
                'default' => esc_html__('Locations', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                    'search_hide' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'loc_lbl',
            [
                'label' => esc_html__('Categorys', 'bookora-plugin'),
                'default' => esc_html__('Categorys', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                    'search_hide' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'tag_lbl',
            [
                'label' => esc_html__('Guests', 'bookora-plugin'),
                'default' => esc_html__('Guests', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                    'search_hide' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'tour_lbl',
            [
                'label' => esc_html__('Toure Type', 'bookora-plugin'),
                'default' => esc_html__('Toure Type', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                    'search_hide' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();



    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/hero-template/hero-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_Hero());