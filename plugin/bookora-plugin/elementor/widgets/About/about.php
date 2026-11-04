<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_About extends Widget_Base
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
        return 'bookora-about';
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
        return esc_html__('Section About', 'bookora-plugin');
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
                'label' => esc_html__('About Style Select', 'bookora-plugin'),
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
        $this->end_controls_section();

        $this->start_controls_section(
            'int_about_opt',
            [
                'label' => esc_html__('About Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'image2',
            [
                'label' => esc_html__('Image 2', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'style!' => ['3'],
                ],
            ]
        );
        $this->add_control(
            'image3',
            [
                'label' => esc_html__('Image 3', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Sub Title', 'bookora-plugin'),
                'default' => esc_html__('Get To Know Us', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'default' => esc_html__('Section Title', 'bookora-plugin'),
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
                'default' => esc_html__('Section Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'default' => esc_html__('Button Label', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label' => esc_html__('Button Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );
        $this->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'prefix',
            [
                'label' => esc_html__('Prefix', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'ct_title',
            [
                'label' => esc_html__('Counter Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'style',
            [
                'label' => esc_html__('Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'bookora-plugin'),
                    '2' => esc_html__('Style 2', 'bookora-plugin')
                ]
            ]
        );
        $repeater->add_control(
            'bg_img',
            [
                'label' => esc_html__('BG Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $repeater->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'prefix',
            [
                'label' => esc_html__('Prefix', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'desc',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );

        $this->add_control(
            'counters',
            [
                'label' => esc_html__('Add Counter Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );
        $this->end_controls_section();

    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/about-template/about-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_About());