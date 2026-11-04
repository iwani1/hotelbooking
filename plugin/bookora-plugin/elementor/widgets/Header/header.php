<?php

/**
 * Elementor Single Widget
 * @package bookora Tools
 * @since 1.0.0
 */

namespace Elementor;

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly.

class Bookora_Header extends Widget_Base
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
		return 'bookora-header-1';
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
		return esc_html__('Header', 'bookora-plugin');
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
			'__header__style__',
			[
				'label' => esc_html__('Header Style Select', 'bookora-plugin'),
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
			'header__general',
			[
				'label' => esc_html__('Header General Option', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'offcanvas',
			[
				'label' => esc_html__('Offcanvas Show Hide', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'bookora-plugin'),
				'label_off' => esc_html__('Hide', 'bookora-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'search_hide',
			[
				'label' => esc_html__('Search Show Hide', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'bookora-plugin'),
				'label_off' => esc_html__('Hide', 'bookora-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'btn_label',
			[
				'label' => esc_html__('Header Button Label', 'bookora-plugin'),
				'default' => esc_html__('Contact Sales', 'bookora-plugin'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__('Header Button Link', 'bookora-plugin'),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_label2',
			[
				'label' => esc_html__('Header Button Two Label', 'bookora-plugin'),
				'default' => esc_html__('Contact Sales', 'bookora-plugin'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_link2',
			[
				'label' => esc_html__('Header Button Two Link', 'bookora-plugin'),
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
			'link',
			[
				'label' => esc_html__('Link', 'bookora-plugin'),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);

		$this->add_control(
			'socials',
			[
				'label' => esc_html__('Add Social Item', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
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

		$this->add_control(
			'infos',
			[
				'label' => esc_html__('Add Info Item', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'logoop',
			[
				'label' => esc_html__('Logo Option', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'rzlogo',
			[
				'label' => esc_html__('Logo', 'bookora-plugin'),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_responsive_control(
			'logo_max_width',
			[
				'label' => esc_html__('Max Width', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					]
				],

				'selectors' => [
					'{{WRAPPER}} .logo_site-size' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'rzmlogo',
			[
				'label' => esc_html__('Mobile Logo', 'bookora-plugin'),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_responsive_control(
			'm_logo_max_width',
			[
				'label' => esc_html__('Max Width', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
					]
				],

				'selectors' => [
					'{{WRAPPER}} .logo_site-m-size' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'menu_select',
			[
				'label' => esc_html__('Menu Option', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'choose-menu',
			[
				'label' => esc_html__('Choose menu', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => bookora_menu_selector(),
				'multiple' => false
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_menu_options',
			[
				'label' => esc_html__('Search Option', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'search_hide' => 'yes',
				],
			]
		);
		$this->add_control(
			'search_title',
			[
				'label' => esc_html__('Search Title', 'bookora-plugin'),
				'default' => esc_html__('Search', 'bookora-plugin'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'search_link_title',
			[
				'label' => esc_html__('Search Link Title', 'bookora-plugin'),
				'default' => esc_html__('People also search for:', 'bookora-plugin'),
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
			'link',
			[
				'label' => esc_html__('Link', 'bookora-plugin'),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);

		$this->add_control(
			'search_link',
			[
				'label' => esc_html__('Add Search Link Item', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'offcanvas_menu_options',
			[
				'label' => esc_html__('Offcanvas Option', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'offcanvas' => 'yes',
				],
			]
		);
		$this->add_control(
			'off_title',
			[
				'label' => esc_html__('About Title', 'bookora-plugin'),
				'default' => esc_html__('About Us', 'bookora-plugin'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'offdesc',
			[
				'label' => esc_html__('Description', 'bookora-plugin'),
				'default' => esc_html__('The argument in favor of using filler text goes something like this: If you use real content in the Consulting Process, anytime you reach a review point you’ll end up reviewing and negotiating the content itself and not the design.', 'bookora-plugin'),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$this->add_control(
			'gallery_title',
			[
				'label' => esc_html__('Gallery Title', 'bookora-plugin'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'gallery',
			[
				'label' => esc_html__('Add Images', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::GALLERY,
				'show_label' => false,
				'default' => [],
			]
		);
		$this->add_control(
			'social_title',
			[
				'label' => esc_html__('Social Title', 'bookora-plugin'),
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
			'link',
			[
				'label' => esc_html__('Link', 'bookora-plugin'),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$this->add_control(
			'f_socials',
			[
				'label' => esc_html__('Add Social Item', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'default' => [
					[
						'icons' => [
							'value' => 'fab fa-facebook-f',
							'library' => 'solid',
						],
						'name' => esc_html__('Facebook', 'bookora-plugin'),
						'link' => esc_html__('https://facebook.com', 'bookora-plugin'),
					],
					[
						'icons' => [
							'value' => 'fab fa-instagram',
							'library' => 'solid',
						],
						'name' => esc_html__('Instagram', 'bookora-plugin'),
						'link' => esc_html__('https://instagram.com', 'bookora-plugin'),
					],
					[
						'icons' => [
							'value' => 'fab fa-youtube',
							'library' => 'solid',
						],
						'name' => esc_html__('Youtube', 'bookora-plugin'),
						'link' => esc_html__('https://youtube.com', 'bookora-plugin'),
					],
					[
						'icons' => [
							'value' => 'fab fa-linkedin',
							'library' => 'solid',
						],
						'name' => esc_html__('Linkedin', 'bookora-plugin'),
						'link' => esc_html__('https://linkedin.com', 'bookora-plugin'),
					]
				],
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'menu_bar_style',
			[
				'label' => esc_html__('Menu  Style', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'--menu--style-info',
			[
				'label' => esc_html__('Menu Style', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_color',
			[
				'label' => esc_html__('Menu Color', 'bookora-plugin'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-main-navigation .navbar-nav li a' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'menu_color-hover',
			[
				'label' => esc_html__('Menu Hover Color', 'bookora-plugin'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-main-navigation .navbar-nav li:hover > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tn-main-navigation .navbar-nav li:is(.dropdown) > a::before' => 'color: {{VALUE}}'

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_fb_typography',
				'selector' => '
					{{WRAPPER}} .tn-main-navigation .navbar-nav li a
				',
			]
		);
		$this->add_control(
			'menu_dropdown_style',
			[
				'label' => esc_html__('Menu Dropdown Style', 'bookora-plugin'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mdb_color',
			[
				'label' => esc_html__('Dropdown Box BG Color', 'bookora-plugin'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-main-navigation .navbar-nav li .dropdown-menu' => 'background: {{VALUE}}'
				],
			]
		);
		$this->add_control(
			'mdb_menu_color',
			[
				'label' => esc_html__('Dropdown Box Border Color', 'bookora-plugin'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-main-navigation .navbar-nav li .dropdown-menu' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_style',
			[
				'label' => esc_html__('Search  Style', 'bookora-plugin'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			's-icon-color',
			[
				'label' => esc_html__('Search Icon Color', 'bookora-plugin'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .arv-header-1-action .search-btn .icon' => 'color: {{VALUE}} !important'
				],
			]
		);
		$this->add_control(
			'search-text-hover',
			[
				'label' => esc_html__('Search Text Color', 'bookora-plugin'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .arv-header-1-action .search-btn .text' => 'color: {{VALUE}} !important'
				],
			]
		);

		$this->end_controls_section();
		// feature style
		$this->start_controls_section(
			'--button_one',
			[
				'label' => esc_html__('Button Style', 'goyto-plugin'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'm_b_typography',
				'selector' => '{{WRAPPER}} .tn-pr-btn-1',
			]
		);
		$this->add_control(
			'padding',
			[
				'label' => esc_html__('Padding', 'goyto-plugin'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .tn-pr-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'b_round',
			[
				'label' => esc_html__('Border Radius', 'goyto-plugin'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .tn-pr-btn-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tn-pr-btn-1::after, .tn-pr-btn-1::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__('Normal', 'goyto-plugin'),
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__('Text Color', 'goyto-plugin'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-pr-btn-1' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tn-pr-btn-1:is(.has-hover-white) .text::before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tn-pr-btn-1 .btn-text' => 'color: {{VALUE}}'
				],
			]
		);
		$this->add_control(
			'btn_br_text',
			[
				'label' => esc_html__('Border Color', 'goyto-plugin'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-pr-btn-1' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'style!' => ['1'],
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color',
				'types' => ['gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .tn-pr-btn-1::after',
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Button BG Color ', 'goyto-plugin'),
						'description' => esc_html__('Choose background type and style.', 'goyto-plugin'),
						'separator' => 'before',
					]
				]
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__('Hover', 'goyto-plugin'),
			]
		);
		$this->add_control(
			'btn_h_text',
			[
				'label' => esc_html__('Text Hovwe Color', 'goyto-plugin'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tn-pr-btn-1:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tn-pr-btn-1:hover .btn-text' => 'color: {{VALUE}}'
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_border_hover_color',
				'types' => ['gradient'],
				'exclude' => ['image'],
				'selector' => '
				{{WRAPPER}} .tn-pr-btn-1::before
				',
				'condition' => [
					'style' => ['1'],
				],
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Button Hover Border Color ', 'goyto-plugin'),
						'description' => esc_html__('Choose background type and style.', 'goyto-plugin'),
						'separator' => 'before',
					]
				]
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_hover_color',
				'types' => ['gradient'],
				'exclude' => ['image'],
				'selector' => '
				{{WRAPPER}} .tn-pr-btn-1:hover,
				{{WRAPPER}} .tn-pr-btn-1:hover::after
				',
				'fields_options' => [
					'background' => [
						'label' => esc_html__('Button Hover BG Color ', 'goyto-plugin'),
						'description' => esc_html__('Choose background type and style.', 'goyto-plugin'),
						'separator' => 'before',
					]
				]
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();



	}


	protected function render()
	{
		$settings = $this->get_settings_for_display();
		extract($settings);
		require __DIR__ . '/header-template/header-' . $settings['style'] . '.php';
	}


	/**
	 * Search Body Display
	 *
	 * @return void
	 */
	protected function ___search_body()
	{
		$settings = $this->get_settings_for_display();
		?>
		<div class="txa-search-box search_box_active ">

			<div class="txa-search-container">
				<div class="txa-search-wrap text-center mb-55">
					<?php if (!empty($settings['search_btn_label'])): ?>
						<h5 class="txa-search-title fx-heading-1 fx-font-500">
							<?php echo bookora_wp_kses($settings['search_title']) ?>
						</h5>
					<?php endif; ?>
					<form action="<?php echo esc_url(home_url('/')); ?>" class="txa-search-form">
						<input class="txa-search-form-input" type="text" name="s" value="<?php echo get_search_query(); ?>"
							placeholder="<?php esc_attr_e('What are you looking for?...', 'bookora-plugin'); ?>">
					</form>

				</div>

				<div class="txa-search-tag-wrap text-center">
					<?php if (!empty($settings['search_link_title'])): ?>
						<h6 class="txa-search-tag-title fx-font-400 fx-heading-1">
							<?php echo bookora_wp_kses($settings['search_link_title']); ?>
						</h6>
					<?php endif; ?>
					<div class="txa-search-tag d-inline-flex flex-wrap">
						<?php foreach ($settings['search_link'] as $item): ?>
							<a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
								rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
								href="<?php echo $item['link']['url'] ? esc_url($item['link']['url']) : ''; ?>" aria-label="name"
								class="txa-search-tag-item fx-heading-1 fx-font-500"><?php echo bookora_wp_kses($item['title']); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<button aria-label="search-close" type="button" class="txa-search-box-close search_box_close">
				<i class="fa-solid fa-x"></i>
			</button>

		</div>
		<!-- search-popup -->

		<div class="overlay"></div>

		<?php
	}

	protected function offcanvasmenu()
	{
		$settings = $this->get_settings_for_display();
		?>
		<div class="xs-sidebar-group info-group">
			<div class="xs-overlay xs-bg-black">
				<div class="row loader-area">
					<div class="col-3 preloader-wrap">
						<div class="loader-bg"></div>
					</div>
					<div class="col-3 preloader-wrap">
						<div class="loader-bg"></div>
					</div>
					<div class="col-3 preloader-wrap">
						<div class="loader-bg"></div>
					</div>
					<div class="col-3 preloader-wrap">
						<div class="loader-bg"></div>
					</div>
				</div>
			</div>
			<div class="xs-sidebar-widget">
				<div class="sidebar-widget-container">
					<div class="widget-heading">
						<a href="#" class="close-side-widget close-side-widget">
							<?php esc_html_e('X', 'bookora-plugin') ?>
						</a>
					</div>
					<div class="sidebar-textwidget">

						<div class="sidebar-info-contents headline pera-content">
							<div class="content-inner headline-2">
								<div class="logo">
									<a href="<?php echo esc_url(home_url()); ?>" aria-label="name">
										<img class="logo_site-m-size" src="<?php echo esc_url($settings['rzmlogo']['url']); ?>"
											alt="<?php if (!empty($settings['rzmlogo']['alt'])) {
												echo esc_attr($settings['rzmlogo']['alt']);
											} ?>">
									</a>
								</div>
								<div class="content-box">
									<?php if (!empty($settings['off_title'])): ?>
										<h5>
											<?php echo bookora_wp_kses($settings['off_title']); ?>
										</h5>
									<?php endif; ?>
									<?php if (!empty($settings['offdesc'])): ?>
										<p class="text"><?php echo bookora_wp_kses($settings['offdesc']); ?></p>
									<?php endif; ?>
								</div>
								<div class="gallery-box ul-li">
									<?php if (!empty($settings['gallery_title'])): ?>
										<h5><?php echo bookora_wp_kses($settings['gallery_title']) ?></h5>
									<?php endif; ?>
									<?php if (!empty($settings['gallery'])): ?>
										<ul class="zoom-gallery">
											<?php foreach ($settings['gallery'] as $item): ?>
												<li><a href="<?php echo esc_url($item['url']); ?>"
														data-source="<?php echo esc_url($item['url']); ?>"><img
															src="<?php echo esc_url($item['url']); ?>" alt=""></a></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
								<div class="content-box">
									<?php if (!empty($settings['social_title'])): ?>
										<h5><?php echo bookora_wp_kses($settings['social_title']); ?></h5>
									<?php endif; ?>
									<?php if (!empty($settings['f_socials'])): ?>
										<ul class="social-box">

											<?php foreach ($settings['f_socials'] as $item): ?>
												<li>
													<a href="<?php echo esc_url($item['link']['url']); ?>">
														<?php \Elementor\Icons_Manager::render_icon($item['icons'], ['aria-hidden' => 'true']); ?>
													</a>
												</li>
											<?php endforeach; ?>

										</ul>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	protected function mobile_menu()
	{
		$settings = $this->get_settings_for_display();
		?>
		<div class="mobile_menu lenis lenis-smooth ofcanvas_sidebar position-relative">
			<div class="mobile_menu_wrap">
				<div class="mobile_menu_overlay open_mobile_menu"></div>
				<div class="mobile_menu_content">
					<div class="mobile_menu_close open_mobile_menu">
						<i class="fas fa-times"></i>
					</div>
					<div class="m-brand-logo">
						<a href="<?php echo esc_url(home_url()); ?>" aria-label="name">
							<img class="logo_site-m-size" src="<?php echo esc_url($settings['rzmlogo']['url']); ?>" alt="<?php if (!empty($settings['rzmlogo']['alt'])) {
								   echo esc_attr($settings['rzmlogo']['alt']);
							   } ?>">
						</a>
					</div>
					<div class="mobile-search-bar position-relative">
						<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
							<input type="text" name="s" id="s"
								placeholder="<?php esc_html_e('Search...', 'bookora-plugin') ?>" />
							<button type="submit"><i class="fas fa-search"></i></button>
						</form>
					</div>
					<nav class="mobile-main-navigation  clearfix ul-li">
						<?php
						echo str_replace(['menu-item-has-children', 'sub-menu'], ['dropdown', 'dropdown-menu clearfix'], wp_nav_menu(array(
							'echo' => false,
							'menu' => !empty($settings['choose-menu']) ? $settings['choose-menu'] : 'menu-1',
							'menu_id' => 'm-main-nav',
							'menu_class' => 'nav navbar-nav clearfix',
							'container' => false,
							'fallback_cb' => 'Navwalker_Class::fallback',
							'walker' => class_exists('Rs_Mega_Menu_Walker') ? new \Rs_Mega_Menu_Walker : '',
						)));
						?>
					</nav>
					<?php if (!empty($settings['f_socials'])): ?>
						<div class="ptx-mobile-header-social text-center">
							<?php foreach ($settings['f_socials'] as $item): ?>
								<a href="<?php echo esc_url($item['link']['url']); ?>">
									<?php \Elementor\Icons_Manager::render_icon($item['icons'], ['aria-hidden' => 'true']); ?>
								</a>
							<?php endforeach; ?>
						</div><?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}



}


Plugin::instance()->widgets_manager->register(new Bookora_Header());