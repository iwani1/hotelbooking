<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly.

class Bookora_Blog extends Widget_Base
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
        return 'bookora-blog-1';
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
        return esc_html__('Bookora Blog', 'bookora-plugin');
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
                'label' => esc_html__('Blog Style Select', 'bookora-plugin'),
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
            'subtitle',
            [
                'label' => esc_html__('Sub Title', 'bookora-plugin'),
                'default' => esc_html__('Get To Know Us', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['2', '3'],
                ],
            ]
        );
        $this->add_control(
            'tagtitle',
            [
                'label' => esc_html__('Info Title', 'bookora-plugin'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'condition' => [
                    'style' => ['3'],
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
                    'style' => ['2', '3'],
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
                    'style' => ['2', '3'],
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
                    'style' => ['2', '3'],
                ],
            ]
        );
        $this->add_control(
            'h_btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'default' => esc_html__('bookora Button', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'style' => ['2', '3'],
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
                    'style' => ['2', '3'],
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'post_sec_h_option',
            [
                'label' => esc_html__('Blog Option', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_order',
            [
                'label' => esc_html__('Post Order', 'bookora-plugin'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC' => esc_html__('Ascending', 'bookora-plugin'),
                    'DESC' => esc_html__('Descending', 'bookora-plugin'),
                ],
            ]
        );

        $this->add_control(
            'post_per_page',
            [
                'label' => __('Posts Per Page', 'bookora-plugin'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 5,
            ]
        );
        $this->add_control(
            'post_categories',
            [
                'type' => Controls_Manager::SELECT2,
                'label' => esc_html__('Select Categories', 'bookora-plugin'),
                'options' => Bookora_Blog_category(),
                'label_block' => true,
                'multiple' => true,
            ]
        );
        $this->add_control(
            'title_length',
            [
                'label' => __('Title Length', 'bookora-tools'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'default' => 20,
            ]
        );
        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'bookora-tools'),
                'type' => Controls_Manager::NUMBER,
                'step' => 1,
                'default' => 20,
            ]
        );
        $this->add_control(
            'btn_label',
            [
                'label' => esc_html__('Button Label', 'bookora-plugin'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'style' => ['1'],
                ],
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'blog_boxc_style',
            [
                'label' => esc_html__('Blog Box Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => esc_html__('Box BG Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-3-item .content' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-1-item' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-9-item' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agt-blg-item-6' => 'background: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'box_date_color',
            [
                'label' => esc_html__('Date BG Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-9-item .item-date' => 'background: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'box_border_color',
            [
                'label' => esc_html__('Box Border Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arv-blog-1-card::after' => 'border-color: {{VALUE}}'
                ],
                'condition' => [
                    'style' => ['1']
                ]

            ]
        );
        $this->add_responsive_control(
            'box_paddint',
            [
                'label' => esc_html__('Box Padding', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'blog_btn_style',
            [
                'label' => esc_html__('Blog Buton Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_btn_bg_color',
            [
                'label' => esc_html__('Post Button BG Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item .blog-btn::after' => 'background: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'post_btn_icon_color',
            [
                'label' => esc_html__('Post Button Icon Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item .blog-btn i' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'blog_style',
            [
                'label' => esc_html__('Blog Style', 'bookora-plugin'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_title',
            [
                'label' => esc_html__('Post Title Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'post_list_title_color',
            [
                'label' => esc_html__('Post Title Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .blog_title' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'post_hover_title_color',
            [
                'label' => esc_html__('Post Title Hover Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-1-item .blog-title:hover' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_title_typography',
                'selector' => '
					{{WRAPPER}} .title
				',
            ]
        );

        $this->add_control(
            'post_date_title',
            [
                'label' => esc_html__('Post Date Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'post_date_bg_color',
            [
                'label' => esc_html__('Post Date BG Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item .item-date' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-1-item .blog-date' => 'background: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'post_date_color',
            [
                'label' => esc_html__('Post Date Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item .item-date' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-1-item .blog-date' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_date_typography',
                'selector' => '
					{{WRAPPER}} .agn-blog-1-item .item-date
				',
            ]
        );

        $this->add_control(
            'post_cate_title',
            [
                'label' => esc_html__('Post Category Style', 'bookora-plugin'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'categor_color',
            [
                'label' => esc_html__('Post Category Color', 'bookora-plugin'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agn-blog-1-item .item-subtitle' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .agn-blog-1-item .item-subtitle' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .arv-blog-1-card .card-content .meta .read-time' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .arv-blog-1-card .card-content .meta .author .name' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_cate_typography',
                'selector' => '
				{{WRAPPER}} .agn-blog-1-item .item-subtitle
				',
            ]
        );

        $this->end_controls_section();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => !empty($settings['post_per_page']) ? $settings['post_per_page'] : 1,
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'order' => $settings['post_order'],
        );
        if (!empty($settings['post_categories'])) {
            $args['category_name'] = implode(',', $settings['post_categories']);
        }

        $query = new \WP_Query($args);
        require __DIR__ . '/template-view/blog-' . $settings['style'] . '.php';
    }
    protected function bookora_category_name()
    {
        $catgorys = get_the_category();

        // Shuffle the array of categories
        shuffle($catgorys);

        // Select a random category
        $random_category = array_rand($catgorys, 1);
        $category = $catgorys[$random_category];

        $meta = get_term_meta($category->term_id, 'barfii_cate_meta', true);
        $catemeta = !empty($meta['cate-color']) ? $meta['cate-color'] : '#3b60fe';
        ?>
        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
            <span><?php echo esc_html($category->cat_name); ?></span>
        </a>
        <?php
    }


}


Plugin::instance()->widgets_manager->register(new Bookora_Blog());