<?php
// functions.php

// Include required files
require_once get_template_directory() . '/includes/class-license-manager.php';
//require_once get_template_directory() . '/includes/tgm-plugin-activation.php';

// Initialize license manager
function theme_init_license_manager() {
    return Theme_License_Manager::get_instance();
}
add_action('init', 'theme_init_license_manager');

// Enqueue admin scripts
function theme_admin_scripts($hook) {
    if ('appearance_page_theme-license' === $hook) {
        wp_enqueue_script('theme-license-js', get_template_directory_uri() . '/assets/js/admin-license.js', array('jquery'), '1.0.0', true);
        wp_enqueue_style('theme-license-css', get_template_directory_uri() . '/assets/css/admin-license.css', array(), '1.0.0');
        
        wp_localize_script('theme-license-js', 'theme_license_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('theme_license_nonce')
        ));
    }
}
add_action('admin_enqueue_scripts', 'theme_admin_scripts');

// Frontend license warning
function theme_display_license_warning() {
    if (!Theme_License_Manager::get_instance()->is_license_active() && current_user_can('manage_options')) {
        get_template_part('templates/license-warning');
    }
}
add_action('wp_footer', 'theme_display_license_warning');
?>