<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_Footer extends Widget_Base
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
        return 'bookora-footer';
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
        return esc_html__('Footer', 'bookora-plugin');
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
            'int_options_opt',
            [
                'label' => esc_html__('Footer Option', 'bookora-plugin'),
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
                    '2' => esc_html__('Style 2', 'bookora-plugin')
                ]
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__('Logo', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );

        $this->add_control(
            'map',
            [
                'label' => esc_html__('Map Iframe', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'newsletter_shortcode',
            [
                'label' => esc_html__('Newsletter Shortcode', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'links_inr',
            [
                'label' => __('Links', 'aivora-plugin'),
                'type' => Controls_Manager::REPEATER,
                'prevent_empty' => false,
                'fields' => [
                    [
                        'name' => 'title',
                        'label' => __('Title', 'aivora-plugin'),
                        'type' => Controls_Manager::TEXT
                    ],
                    [
                        'name' => 'link',
                        'label' => __('Link', 'aivora-plugin'),
                        'type' => Controls_Manager::URL,
                        'placeholder' => __('https://your-link.com', 'aivora-plugin'),
                        'default' => [
                            'url' => '#',
                        ],
                    ],
                ],
            ],
        );

        $this->add_control(
            'links',
            [
                'label' => esc_html__('Add Footer Link Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
            ]
        );

        $this->add_control(
            'rsv_title',
            [
                'label' => esc_html__('Rsv Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'rvp_icon',
            [
                'label' => esc_html__('Rsv Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'info',
            [
                'label' => esc_html__('Info', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'reserves',
            [
                'label' => esc_html__('Add Reservation Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'ct_icon',
            [
                'label' => esc_html__('Contact Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'ct_title',
            [
                'label' => esc_html__('Contact Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'info',
            [
                'label' => esc_html__('Info Only footer two', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'contacts',
            [
                'label' => esc_html__('Add Contact Indo Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'bookora-plugin'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Links', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'socials',
            [
                'label' => esc_html__('Add Socials Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
            ]
        );

        $this->add_control(
            'copyright',
            [
                'label' => esc_html__('Copyright', 'riverhouse-plugin'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->add_control(
            'developer_credit',
            [
                'label' => esc_html__('Developer Credit', 'riverhouse-plugin'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );
        $this->end_controls_section();

    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/footer-template/footer-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_Footer());