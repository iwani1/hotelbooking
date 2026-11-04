<?php
/**
 * Elementor Widget Initialization
 * @package bookora
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('bookora_Elementor_Widgets_Init')) {

	class bookora_Elementor_Widgets_Init
	{
		private static ?self $instance = null;

		public function __construct()
		{
			// Elementor hooks
			add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
			add_action('elementor/widgets/register', [$this, 'register_widgets']);
			add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles']);
			add_action('elementor/documents/register_controls', [$this, 'register_page_controls']);

			// Frontend styles
			add_action('wp_enqueue_scripts', [$this, 'register_widget_styles']);
		}

		public static function get_instance(): self
		{
			if (self::$instance === null) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Register Elementor widget category
		 */
		public function register_widget_category($elements_manager)
		{
			$elements_manager->add_category(
				'bookora_widgets',
				[
					'title' => __('bookora Addons', 'element-helper'),
					'icon' => 'fa fa-plug',
				]
			);
		}

		/**
		 * Include all custom widgets
		 */
		public function register_widgets()
		{
			$widgets = glob(plugin_dir_path(__FILE__) . 'widgets/*/*.php');
			foreach ($widgets as $widget) {
				require_once $widget;
			}
		}

		/**
		 * Register frontend styles
		 */
		public function register_widget_styles()
		{
			$styles = [
				'toure',
				'donation',
				'info-box',
				'cta',
				'faq',
				'newsletter',
				'location',
			];

			foreach ($styles as $style) {
				wp_register_style(
					$style,
					BOOKORA_DIR_URL . "assets/css/{$style}.css"
				);
			}
		}

		/**
		 * Add custom editor styles
		 */
		public function enqueue_editor_styles()
		{
			$icon_url = plugins_url('icons.png', __FILE__);
			$custom_css = ".elementor-element .icon .bookora-custom-icon { content: url({$icon_url}); width: 28px; }";
			wp_add_inline_style('elementor-editor', $custom_css);
		}

		/**
		 * Register custom page-level controls
		 */
		public function register_page_controls($document)
		{
			if (!$document instanceof \Elementor\Core\DocumentTypes\PageBase || !$document::get_property('has_elements')) {
				return;
			}

			$document->start_controls_section(
				'body_bookora_style',
				[
					'label' => esc_html__('bookora Custom Body Style', 'bookora-plugin'),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			// Body color
			$document->add_control(
				'body_color',
				[
					'label' => esc_html__('Body Color', 'textdomain'),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}' => 'color: {{VALUE}};',
					],
				]
			);

			// Typography
			$document->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'page_body_font',
					'label' => esc_html__('Page Body Font', 'textdomain'),
					'selector' => '{{WRAPPER}}',
				]
			);

			$document->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'page_heading_font',
					'label' => esc_html__('Page Heading Font', 'textdomain'),
					'selector' => '{{WRAPPER}} h1, h2, h3, h4, h5, h6',
				]
			);

			// Heading color
			$document->add_control(
				'h_color',
				[
					'label' => esc_html__('Heading Color', 'textdomain'),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} h1, h2, h3, h4, h5, h6' => 'color: {{VALUE}} !important;',
					],
				]
			);

			// Background
			$document->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'body_cs_bg_color',
					'types' => ['classic'],
					'selector' => '{{WRAPPER}} .agt-home-8:before',
					'fields_options' => [
						'background' => [
							'label' => esc_html__('Body Custom BG Image', 'bookora-plugin'),
							'description' => esc_html__('Choose background type and style.', 'bookora-plugin'),
							'separator' => 'before',
						]
					]
				]
			);

			// Page width
			$document->add_responsive_control(
				'page_width',
				[
					'label' => esc_html__('Container Width', 'bookora-plugin'),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px', '%', 'em', 'rem', 'custom'],
					'selectors' => [
						'{{WRAPPER}} .container' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$document->end_controls_section();
		}
	}

	// Initialize the class
	bookora_Elementor_Widgets_Init::get_instance();
}
