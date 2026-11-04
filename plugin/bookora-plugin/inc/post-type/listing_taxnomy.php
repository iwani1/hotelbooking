<?php

if (!function_exists('bookora_tour_type_tax')) {
    function bookora_tour_type_tax()
    {
        $labels = [
            'name' => esc_html__('Tour Type', 'bookora-plugin'),
            'menu_name' => esc_html__('Tour Type', 'bookora-plugin'),
            'singular_name' => esc_html__('Tour Type', 'bookora-plugin'),
            'search_items' => esc_html__('Search Type', 'bookora-plugin'),
            'all_items' => esc_html__('All Type', 'bookora-plugin'),
            'new_item_name' => esc_html__('New Type', 'bookora-plugin'),
            'add_new_item' => esc_html__('Add New Type', 'bookora-plugin'),
            'edit_item' => esc_html__('Edit New Type', 'bookora-plugin'),
            'update_item' => esc_html__('Update New Type', 'bookora-plugin'),
        ];
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'update_count_callback' => '_update_post_term_count',
        );
        register_taxonomy('tour_type', 'at_biz_dir', $args);
    }
    add_action('init', 'bookora_tour_type_tax');
}