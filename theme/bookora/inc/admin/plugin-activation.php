<?php

add_action('tgmpa_register', 'bookora_register_required_plugins');

function bookora_register_required_plugins()
{

    $plugins = array(
        array(
            'name' => esc_html__('Bookora Plugin', 'bookora'),
            'slug' => 'bookora-plugin',
            'source' => ('https://themexriver.com/wp/bookora/plugin/bookora-plugin.zip'),
            'required' => true,
            'force_activation' => false,
            'force_deactivation' => false,
        ),
        array(
            'name' => 'Elementor Website Builder',
            'slug' => 'elementor',
            'required' => true,
        ),

        array(
            'name' => esc_html__('Contact Form 7', 'bookora'),
            'slug' => 'contact-form-7',
            'required' => false,
        ),

        array(
            'name' => esc_html__('One Click Demo Import', 'bookora'),
            'slug' => 'one-click-demo-import',
            'required' => true,
        ),

        array(
            'name' => esc_html__('WooCommerce', 'bookora'),
            'slug' => 'woocommerce',
            'required' => true,
        ),
        array(
            'name' => esc_html__('AI-Powered Business Directory Plugin with Classified Ads Listings', 'bookora'),
            'slug' => 'directorist',
            'required' => true,
        ),
        array(
            'name' => esc_html__('Mailchimp for WordPress', 'bookora'),
            'slug' => 'mailchimp-for-wp',
            'required' => true,
        )

    );

    $config = array(
        'id' => 'bookora',
        'parent_slug' => 'bookora',
        'menu' => 'tgmpa-install-plugins',
        'dismissable' => true,
        'dismiss_msg' => '',
        'is_automatic' => false,
        'message' => '',
        'default_path' => '',
    );

    tgmpa($plugins, $config);
}
