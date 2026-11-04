<?php

if (!function_exists('bookora_register_custom_icon_library')) {
    add_filter('elementor/icons_manager/additional_tabs', 'bookora_register_custom_icon_library');
    function bookora_register_custom_icon_library($tabs)
    {
        $custom_tabs = [
            'extra_icon2' => [
                'name' => 'bookora-flat-icon',
                'label' => esc_html__('Flaticon', 'bookora'),
                'url' => BOOKORA_DIR_URL . '/assets/css/flaticon_mycollection.css',
                'enqueue' => [BOOKORA_DIR_URL . '/assets/css/flaticon_mycollection.css'],
                'prefix' => 'flaticon-',
                'displayPrefix' => 'flaticon',
                'labelIcon' => 'flaticon-trophy',
                'ver' => BOOKORA_VERSION,
                'fetchJson' => BOOKORA_DIR_URL . 'assets/js/falticon.js?v=' . BOOKORA_VERSION,
                'native' => true,
            ]


        ];

        $tabs = array_merge($custom_tabs, $tabs);

        return $tabs;
    }
}