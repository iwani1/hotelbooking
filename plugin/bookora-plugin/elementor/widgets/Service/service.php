<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_Service extends Widget_Base
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
        return 'bookora-service';
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
        return esc_html__('Service', 'bookora-plugin');
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
                    '3' => esc_html__('Style 3', 'bookora-plugin')
                ]
            ]
        );
        $this->add_control(
            'bg_img',
            [
                'label' => esc_html__('BG Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
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
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'bookora-plugin'),
                'default' => esc_html__('Section Title', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '3'],
                ],
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
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );
        $this->add_control(
            'descripiton',
            [
                'label' => esc_html__('Descripiton', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'condition' => [
                    'style' => ['1', '3'],
                ],
            ]
        );
        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'default' => esc_html__('bookora Button', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $this->add_control(
            'btn_link',
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
                    'style' => ['1'],
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            '--bookora-option',
            [
                'label' => esc_html__('Service Option', 'bookora-plugin'),
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

        $this->add_control(
            'services',
            [
                'label' => esc_html__('Add Service Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bookora--count-stle',
            [
                'label' => esc_html__('Count Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__('Count Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Count Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .number' => 'color: {{VALUE}}'
                ],
            ]
        );

        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [

                'name' => 'counts_typography',
                'selector' => '
					{{WRAPPER}} .number
				',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bookora--title-stle',
            [
                'label' => esc_html__('Title Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'ttl_margin',
            [
                'label' => esc_html__('Title Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Title Hover Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title:hover' => 'color: {{VALUE}}'
                ],
            ]
        );
        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [

                'name' => 'm_s_typography',
                'selector' => '
					{{WRAPPER}} .title
				',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bookora--desc-stle',
            [
                'label' => esc_html__('Desc Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'ttl_desc_margin',
            [
                'label' => esc_html__('Desc Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .disc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__('Desc Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .disc' => 'color: {{VALUE}}'
                ],
            ]
        );

        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [

                'name' => 'desc_typography',
                'selector' => '
					{{WRAPPER}} .disc
				',
            ]
        );


        $this->end_controls_section();

    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/service-template/service-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_Service());