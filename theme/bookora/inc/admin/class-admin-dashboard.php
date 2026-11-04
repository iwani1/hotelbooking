<?php

/**
 * [Bookora_Aadmin description]
 */
if (!class_exists('Bookora_Aadmin')) {
    class Bookora_Aadmin
    {

        private static $instance = null;

        /**
         * register instance
         *
         * @return void
         */
        public static function init()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * init Construct
         */
        public function __construct()
        {
            add_action('init', [$this, 'bookora_tgm_dashboard'], 1);
            add_action('admin_menu', [$this, 'bookora_bookora_dashboard'], 1);
            add_action('admin_menu', [$this, 'bookora_template_dashboard'], 20);
            add_action('ocdi/plugin_page_setup', [$this, 'bookora_import_dsb'], 20);
            add_action('admin_enqueue_scripts', array($this, 'bookora_admin_enqueue_scripts'));
            add_action('admin_menu', [$this, 'bookora_megamenu_dashboard'], 21);
        }

        /**
         * Admin Dashboard
         *
         * @return void
         */
        public function bookora_bookora_dashboard()
        {
            add_menu_page(
                esc_html__('Bookora', 'bookora'),
                esc_html__('Bookora', 'bookora'),
                'manage_options',
                'bookora',
                [$this, 'display_bookora_bookora_dashboard'],
                get_template_directory_uri() . '/inc/admin/assets/img/favicon.png',
                2
            );


        }

        /**
         * Template Dashboard
         *
         * @return void
         */
        public function bookora_template_dashboard()
        {
            add_submenu_page(
                'bookora',
                esc_html__('Templates', 'bookora'),
                esc_html__('Templates', 'bookora'),
                'manage_options',
                'edit.php?post_type=bookora_template',
                false
            );
        }

        /**
         * admin style Add
         */
        public function bookora_admin_enqueue_scripts()
        {
            wp_enqueue_style('bookora-admin', get_theme_file_uri('inc/admin/assets/css/admin.css'), array(), null, 'all');
        }

        public function display_bookora_bookora_dashboard()
        {
            require_once BOOKORA_INC_DRI . 'admin/admin-page.php';
        }

        public function bookora_tgm_dashboard()
        {
            require_once BOOKORA_INC_DRI . 'admin/class-tgm-plugin-activation.php';
            require_once BOOKORA_INC_DRI . 'admin/plugin-activation.php';
        }

        public function bookora_import_dsb($default)
        {
            $default['parent_slug'] = 'bookora';
            $default['page_title'] = esc_html__('One Click Demo Import', 'bookora');
            $default['menu_title'] = esc_html__('Import Demo Data', 'bookora');
            $default['capability'] = 'import';
            $default['menu_slug'] = 'one-click-demo-import';

            return $default;
        }

        /**
         * Megamenu Elementor Template
         *
         * @return  [type]  [return description]
         */
        public function bookora_megamenu_dashboard()
        {
            add_submenu_page(
                'bookora',
                esc_html__('Mega Menu', 'bookora'),
                esc_html__('Mega Menu', 'bookora'),
                'manage_options',
                'edit.php?post_type=th-mega-menu',
                false
            );
        }
    }
    Bookora_Aadmin::init();
}
