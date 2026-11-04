<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_Pricing extends Widget_Base
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
        return 'go-pricing';
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
        return esc_html__('Pricing', 'bookora-plugin');
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
            '--pricing-option',
            [
                'label' => esc_html__('pricing Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
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
            'description',
            [
                'label' => esc_html__('Description', 'bookora-plugin'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => esc_html__('Price', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'currency',
            [
                'label' => esc_html__('Currency', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $this->add_control(
            'period',
            [
                'label' => esc_html__('Period', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
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
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'lists',
            [
                'label' => esc_html__('Add List Item', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'prevent_empty' => false,
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'abouttxt-style',
            [
                'label' => esc_html__('Testtimonial Text Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => ['1', '3', '4', '6', '8'],
                ],
            ]
        );


        $this->add_control(
            'tst_text',
            [
                'label' => esc_html__('Text Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arv-pricing-1-item .client-content blockquote' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .arv-pricing-1-item .client-content .client-bio .role' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-tst-item-3 .item-text p' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-tst-item-3 .author-name .item-name span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-ft-testi-item .item-rate-text p' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-ft-testi-item .item-author .item-text span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-quote-content .item-text h3' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-quote-content .item-img-name .item-name span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agt-quote-content .item-img-name .item-name p' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'authores_box',
            [
                'label' => esc_html__('Authore Text Box Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-quote-content .item-img-name .item-name' => 'background: {{VALUE}}'
                ],
                'condition' => [
                    'style' => ['8'],
                ],
            ]
        );


        $this->end_controls_section();
        // title style
        $this->start_controls_section(
            'aboutbox-style',
            [
                'label' => esc_html__('Box Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => ['2', '1', '3', '4', '5', '6', '8', '7'],
                ],
            ]
        );


        $this->add_control(
            'box_bg',
            [
                'label' => esc_html__('Box BG Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-tst-item .item-inner:before' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .arv-pricing-1-bg' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agt-tst-item-3' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agn-pricing-5-area' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agt-ft-testi-item' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agt-quote-section' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agt-testi-item-6 .item-wrap' => 'background: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'box_border_bg',
            [
                'label' => esc_html__('Box Border Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agt-ft-testi-item' => 'border-color: {{VALUE}}'
                ],
            ]
        );


        $this->end_controls_section();
        // Sub title style
        $this->start_controls_section(
            'slider_sub_title_style',
            [
                'label' => esc_html__('Sub Title Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $this->add_responsive_control(
            'margin',
            [
                'label' => esc_html__('Sub Title Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-bookora-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__('Sub Title Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-bookora-sub' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'sub_line_color',
            [
                'label' => esc_html__('Sub Line BG Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arv-subtitle-1 span' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agn-brand-2-sec-title .line' => 'background: {{VALUE}}'
                ],
            ]
        );

        // typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [

                'name' => 'm_s_typography',
                'selector' => '{{WRAPPER}} .elementor-bookora-sub',
            ]
        );


        $this->end_controls_section();
        // title style
        $this->start_controls_section(
            'slider_title_style',
            [
                'label' => esc_html__('Title Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Sub Title Margin', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-gt-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [

                'name' => 'm_t_typography',
                'selector' => '{{WRAPPER}} .elementor-gt-heading',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-gt-heading' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();



    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        require __DIR__ . '/pricing-template/pricing-1.php';
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_Pricing());