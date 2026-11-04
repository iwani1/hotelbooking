<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class bookora_Project extends Widget_Base
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
        return 'bookora-project';
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
        return esc_html__('Project', 'bookora-plugin');
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'default' => esc_html__('bookora Button', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
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
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '--bookora-option',
            [
                'label' => esc_html__('bookora Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
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
            ]
        );

        $this->add_control(
            'projects',
            [
                'label' => esc_html__('Add project Item', 'bookora-plugin'),
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
        require __DIR__ . '/project-template/project-' . $settings['style'] . '.php';
    }


}


Plugin::instance()->widgets_manager->register(new bookora_Project());