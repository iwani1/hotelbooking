<?php
// includes/tgm-plugin-activation.php

require_once get_template_directory() . '/includes/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'theme_register_required_plugins');

function theme_register_required_plugins() {
    $license_manager = Theme_License_Manager::get_instance();
    
    // Only show plugin installation if license is active
    if (!$license_manager->is_license_active()) {
        return;
    }
    
    $plugins = array(
        array(
            'name'     => 'Elementor Page Builder',
            'slug'     => 'elementor',
            'required' => true,
        ),
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'theme-tgmpa',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );

    tgmpa($plugins, $config);
}
?>