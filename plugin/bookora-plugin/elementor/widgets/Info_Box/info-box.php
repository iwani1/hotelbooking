<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class bookora_Info extends Widget_Base
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
        return 'bookora-info';
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
        return esc_html__('Info Box', 'bookora-plugin');
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
                    '1' => esc_html__('Style 1', 'bookora-plugin'),
                    '2' => esc_html__('Style 2', 'bookora-plugin'),
                    '3' => esc_html__('Style 3', 'bookora-plugin'),
                    '4' => esc_html__('Style 4', 'bookora-plugin')
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            '--bookora-option',
            [
                'label' => esc_html__('Info Box Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '4'],
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'info',
            [
                'label' => esc_html__('Info Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
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
            'link',
            [
                'label' => esc_html__('Button Link', 'bookora-plugin'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    // 'custom_attributes' => '',
                ],
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '4'],
                ],
            ]
        );
        $this->end_controls_section();

    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/infobox-template/info-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new bookora_Info());