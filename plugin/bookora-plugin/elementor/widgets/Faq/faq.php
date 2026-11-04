<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class bookora_Accordion extends Widget_Base
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
        return 'bookora-faq';
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
        return esc_html__('bookora Accordion', 'bookora-plugin');
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
                'label' => esc_html__('Faq Select', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__('Faq Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'bookora-plugin'),
                    '2' => esc_html__('Style 1', 'bookora-plugin')
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            '--faq-option',
            [
                'label' => esc_html__('FAQ Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'anims',
            [
                'label' => esc_html__('Animation Speed', 'gtbus-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'gtbus-plugin'),
                'default' => esc_html__('market trends analysis', 'gtbus-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'gtbus-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'faqs',
            [
                'label' => esc_html__('Add Faq Item', 'goyto-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'faq__box_style',
            [
                'label' => esc_html__('Faq Box Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'faq_box-active_color',
            [
                'label' => esc_html__('Box BG Active Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-fq-accordion .accordion-item.faq_active' => 'background: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'faq_box-act_border_color',
            [
                'label' => esc_html__('Box Border Active Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-fq-accordion .accordion-item.faq_active' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'faq_box_color',
            [
                'label' => esc_html__('Box BG Active Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-fq-accordion .accordion-item' => 'background: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'faq_box_border_color',
            [
                'label' => esc_html__('Box Border Active Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-fq-accordion .accordion-item' => 'border-color: {{VALUE}}'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'faq__title_style',
            [
                'label' => esc_html__('Faq Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'ttl_margin',
            [
                'label' => esc_html__('Faq Title Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .agn-accordion-item .item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .agt-fq-accordion5 .accordion-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-accordion-item .item-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-fq-accordion .accordion-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-fq-accordion5 .accordion-button' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'title_active_color',
            [
                'label' => esc_html__('Title Active Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-accordion-item .item-title:is(:not(.collapsed))' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-fq-accordion .accordion-item.faq_active .accordion-button' => 'color: {{VALUE}}'
                ],
            ]
        );
        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'm_s_typography',
                'selector' => '
					{{WRAPPER}} .agn-accordion-item .item-title,
					{{WRAPPER}} .agt-fq-accordion .accordion-button,
					{{WRAPPER}} .agt-fq-accordion5 .accordion-button
				',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'faq__desc_style',
            [
                'label' => esc_html__('Faq Desc Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'faq_dsc_margin',
            [
                'label' => esc_html__('Faq Desc Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .agn-p-1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .agn-p-9' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .agt-fq-accordion5 .accordion-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'faq_dec_color',
            [
                'label' => esc_html__('Faq Desc Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-p-1' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agn-p-9' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-fq-accordion5 .accordion-body' => 'color: {{VALUE}}'
                ],
            ]
        );

        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'faq_desc_typography',
                'selector' => '
					{{WRAPPER}} .agn-p-1,
					{{WRAPPER}} .agn-p-9,
					{{WRAPPER}} .agt-fq-accordion5 .accordion-body
				',
            ]
        );
        $this->end_controls_section();

        // Sub title style
        $this->start_controls_section(
            'faq_h_title_style',
            [
                'label' => esc_html__('Faq Heading Title Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => '4',
                ],
            ]
        );
        $this->add_responsive_control(
            'faq_hmargin',
            [
                'label' => esc_html__('Title Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-bookora-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'faq_title_color',
            [
                'label' => esc_html__('Sub Title Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-bookora-sub' => 'color: {{VALUE}}'
                ],
            ]
        );


        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [

                'name' => 'faq_s_typography',
                'selector' => '{{WRAPPER}} .elementor-bookora-sub',
            ]
        );


        $this->end_controls_section();

    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $template = __DIR__ . '/faq-template/faq-' . $settings['style'] . '.php';

        if (file_exists($template)) {
            include $template;
        } else {
            echo '<p style="color:red;">Template not found: faq-' . esc_html($settings['style']) . '.php</p>';
        }
    }


}


Plugin::instance()->widgets_manager->register(new bookora_Accordion());