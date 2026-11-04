<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_Facilities extends Widget_Base
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
        return 'bookora-facilities';
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
        return esc_html__('Facilities', 'bookora-plugin');
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
                'label' => esc_html__('bookora Style Select', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__('bookora Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'bookora-plugin')
                ]
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
            'descripiton',
            [
                'label' => esc_html__('Descripiton', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '--bookora-option',
            [
                'label' => esc_html__('facilities Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'type',
            [
                'label' => __('Icon Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'bookora-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => __('Image', 'bookora-core'),
                        'icon' => 'fa fa-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,

            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'icon_img',
            [
                'label' => esc_html__('Icon Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'type' => 'image',
                ],
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'class',
            [
                'label' => esc_html__('Custom Class', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'facilitiess',
            [
                'label' => esc_html__('Add facilities Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'type',
            [
                'label' => __('Icon Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'bookora-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => __('Image', 'bookora-core'),
                        'icon' => 'fa fa-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,

            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'icon_img',
            [
                'label' => esc_html__('Icon Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'type' => 'image',
                ],
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'class',
            [
                'label' => esc_html__('Custom Class', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'facilitiess2',
            [
                'label' => esc_html__('Add facilities 2 Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'type',
            [
                'label' => __('Icon Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'bookora-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => __('Image', 'bookora-core'),
                        'icon' => 'fa fa-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,

            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'icon_img',
            [
                'label' => esc_html__('Icon Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'type' => 'image',
                ],
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'class',
            [
                'label' => esc_html__('Custom Class', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'facilitiess3',
            [
                'label' => esc_html__('Add facilities 3 Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'type',
            [
                'label' => __('Icon Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'bookora-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => __('Image', 'bookora-core'),
                        'icon' => 'fa fa-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,

            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'icon_img',
            [
                'label' => esc_html__('Icon Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'type' => 'image',
                ],
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'class',
            [
                'label' => esc_html__('Custom Class', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'facilitiess4',
            [
                'label' => esc_html__('Add facilities 4 Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'type',
            [
                'label' => __('Icon Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'bookora-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => __('Image', 'bookora-core'),
                        'icon' => 'fa fa-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,

            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'icon_img',
            [
                'label' => esc_html__('Icon Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'type' => 'image',
                ],
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'class',
            [
                'label' => esc_html__('Custom Class', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'facilitiess5',
            [
                'label' => esc_html__('Add facilities 5 Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'type',
            [
                'label' => __('Icon Type', 'bookora-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'icon' => [
                        'title' => __('Icon', 'bookora-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => __('Image', 'bookora-core'),
                        'icon' => 'fa fa-image',
                    ],
                ],
                'default' => 'icon',
                'toggle' => false,
                'style_transfer' => true,

            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'icon_img',
            [
                'label' => esc_html__('Icon Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'type' => 'image',
                ],
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'class',
            [
                'label' => esc_html__('Custom Class', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'facilitiess6',
            [
                'label' => esc_html__('Add facilities 6 Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );

        $this->end_controls_section();


    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/facilities-template/facilities-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_Facilities());