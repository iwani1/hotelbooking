<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_Property extends Widget_Base
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
        return 'bookora-property';
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
        return esc_html__('Property', 'bookora-plugin');
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
                'label' => esc_html__('Property Style', 'bookora-plugin'),
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

        $this->end_controls_section();

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );



        $this->add_control(
            'properties_count',
            [
                'label' => esc_html__('Number of Properties to Show', 'bookora-plugin'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50,
                'step' => 1,
                'default' => 6,
            ]
        );

        $this->add_control(
            'porp_tags',
            array(
                'label' => esc_html__('Tags Filter', 'themexriver-core'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => array_flip(barfii_item_tag_lists('tags', array(
                    'sort_order' => 'ASC',
                    'taxonomies' => 'at_biz_dir-tags',
                    'hide_empty' => false,
                ))),
                'label_block' => true,
            )
        );
        $this->add_control(
            'price_text',
            [
                'label' => esc_html__('Price Time', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Night', 'bookora-plugin'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Night', 'bookora-plugin'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Style Controls
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_image',
            [
                'label' => esc_html__('Background Image', 'bookora-plugin'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'section_padding_top',
            [
                'label' => esc_html__('Padding Top (px)', 'bookora-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 125,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rh-top-property-sec' => 'padding-top: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'section_padding_bottom',
            [
                'label' => esc_html__('Padding Bottom (px)', 'bookora-plugin'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 125,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rh-top-property-sec' => 'padding-bottom: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get Directorist categories for filter
     */
    private function get_directorist_categories()
    {
        $categories = get_terms([
            'taxonomy' => 'at_biz_dir-category',
            'hide_empty' => true,
        ]);

        $filter_options = ['*' => 'All'];

        foreach ($categories as $category) {
            $filter_options[$category->slug] = $category->name;
        }

        return $filter_options;
    }

    /**
     * Format price with currency
     */
    private function format_price($price)
    {
        if (empty($price) || $price === 'Price on request') {
            return 'Price on request';
        }

        // Get Directorist currency settings
        $currency_symbol = get_directorist_option('g_currency_symbol', '$');
        $currency_position = get_directorist_option('g_currency_position', 'before');

        // Format the price with thousand separator
        $formatted_price = number_format(floatval($price), 0, '.', ',');

        // Add currency symbol based on position
        if ($currency_position === 'before') {
            return $currency_symbol . $formatted_price;
        } else {
            return $formatted_price . $currency_symbol;
        }
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();


        // Background image
        $bg_image = !empty($settings['bg_image']['url']) ? $settings['bg_image']['url'] : 'assets/img/bg/tp-bg.jpg';
        require __DIR__ . '/property-template/property-' . $settings['style'] . '.php';
    }
}

Plugin::instance()->widgets_manager->register(new Bookora_Property());