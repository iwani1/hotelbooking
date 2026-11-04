<?php
if (!function_exists('bookora_page_template_type')) {
	function bookora_page_template_type()
	{
		register_post_type(
			'bookora_template',
			array(
				'labels' => array(
					'name' => __('Template', 'bookora-plugin'),
					'singular_name' => __('Template', 'bookora-plugin')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_in_menu' => false,
				'show_in_nav_menus' => false,
				'supports' => ['title', 'elementor']
			)
		);
	}
	add_action('init', 'bookora_page_template_type', 2);
}
