<?php
/*
Plugin Name: Bookora Plugin
Plugin URI: https://themeforest.net/user/themexriver
Description: After installing the bookora WordPress Theme, you must install this plugin to access all theme features.
Author: Raziul Islam
Author URI: https://themexriver.com/
Version: 1.0
Text Domain: bookora-plugin
*/

if (!defined('ABSPATH')) {
    exit;
}

final class Bookora_Plugin
{

    public function __construct()
    {
        $this->define_constants();
        $this->load_textdomain();
        $this->includes();
        $this->hooks();
    }

    private function define_constants()
    {
        define('BOOKORA_VERSION', '1.0.0');
        define('BOOKORA_DIR_PATH', plugin_dir_path(__FILE__));
        define('BOOKORA_DIR_URL', plugin_dir_url(__FILE__));
        define('BOOKORA_INC_PATH', BOOKORA_DIR_PATH . 'inc');
        define('BOOKORA_PLUGIN_IMG_PATH', BOOKORA_DIR_URL . 'assets/img');
    }

    private function load_textdomain()
    {
        add_action('plugins_loaded', function () {
            load_plugin_textdomain('bookora-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
        });
    }

    private function includes()
    {
        // Framework
        if (file_exists(BOOKORA_DIR_PATH . 'lib/codestar-framework/codestar-framework.php')) {
            require_once BOOKORA_DIR_PATH . 'lib/codestar-framework/codestar-framework.php';
        }

        // Megamenu
        if (!class_exists('Rs_MegaMenu_Register')) {
            require_once BOOKORA_DIR_PATH . 'mega-menu/class-megamenu.php';
            Rs_MegaMenu_Register::get_instance();
        }

        // Include core plugin files
        require_once BOOKORA_INC_PATH . '/custom-font.php';
        require_once BOOKORA_INC_PATH . '/custom-widget/recent-post.php';
        require_once BOOKORA_INC_PATH . '/bookora-plugin-helper.php';
        require_once BOOKORA_INC_PATH . '/helper.php';
        require_once BOOKORA_INC_PATH . '/constim-icon.php';
        require_once BOOKORA_INC_PATH . '/csf-custom-icon.php';
        require_once BOOKORA_INC_PATH . '/post-type/template.php';
        require_once BOOKORA_INC_PATH . '/post-type/listing_taxnomy.php';
        require_once BOOKORA_DIR_PATH . 'elementor/elementor-init.php';

        add_action('after_setup_theme', [$this, 'after_setup_theme']);
    }

    public function after_setup_theme()
    {
        require_once BOOKORA_INC_PATH . '/options/theme-metabox.php';
        require_once BOOKORA_INC_PATH . '/options/theme-option.php';
    }

    private function hooks()
    {
        // Admin styles
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);

        // Widgets
        add_action('widgets_init', [$this, 'register_widgets']);

        // CF7 Filter
        add_filter('wpcf7_autop_or_not', '__return_false');

    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style('bookora-admin-style', BOOKORA_DIR_URL . 'assets/css/admin-style.css', false, BOOKORA_VERSION);
        wp_enqueue_style('flaticon_multipurpose_agency', BOOKORA_DIR_URL . 'assets/css/flaticon_multipurpose_agency.css');
    }

    public function enqueue_frontend_assets()
    {
        wp_enqueue_style('flaticon_multipurpose_agency', BOOKORA_DIR_URL . 'assets/css/flaticon_multipurpose_agency.css');
        wp_enqueue_script('bookora-addon-core', BOOKORA_DIR_URL . 'assets/js/core.js', ['jquery'], BOOKORA_VERSION, true);
    }

    public function register_widgets()
    {
        register_widget('bookora_Recent_Posts');
    }
}

// Initialize plugin
new Bookora_Plugin();
add_filter('wp_lazy_loading_enabled', '__return_false');