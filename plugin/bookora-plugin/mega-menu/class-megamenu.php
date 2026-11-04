<?php

if (!defined('ABSPATH')) {
    die();
}
require_once(BOOKORA_DIR_PATH . 'mega-menu/class-megamenu-walker.php');

class Rs_MegaMenu_Register
{
    /**
     * Core singleton class
     *
     * @var self - pattern realization
     * @access private
     */
    private static $_instance;

    private $enable_megamenu;

    private $menu_meta_extra = array();


    /**
     * Constructor
     *
     * @access private
     */
    function __construct()
    {

        add_action('init', array($this, 'init'), 0);
        add_action('admin_init', array($this, 'ct_admin_init'), 20);

        // Custom Fields - Add
        add_filter('wp_setup_nav_menu_item', array($this, 'setup_nav_menu_item'));

        // Custom Fields - Save
        add_action('wp_update_nav_menu_item', array($this, 'update_nav_menu_item'), 100, 3);

        // Custom Walker - Edit
        add_filter('wp_edit_nav_menu_walker', array($this, 'edit_nav_menu_walker'), 100, 2);

        add_action('init', array($this, 'register_mega_menu_type'));
    }

    function register_mega_menu_type()
    {
        unregister_nav_menu('key');
    }

    function ct_admin_init()
    {
        $this->menu_meta_extra = apply_filters("ct_menu_edit", array());
    }

    function init()
    {
        $this->enable_megamenu = apply_filters('th_enable_megamenu', false);
        if ($this->enable_megamenu === true) {
            $labels = array(
                'name' => _x('Mega Menus', 'Post Type General Name', 'ximsa'),
                'singular_name' => _x('Mega Menu', 'Post Type Singular Name', 'ximsa'),
                'menu_name' => __('Mega Menus', 'ximsa'),
                'name_admin_bar' => __('Mega Menus', 'ximsa'),
                'archives' => __('Item Archives', 'ximsa'),
                'parent_item_colon' => __('Parent Item:', 'ximsa'),
                'all_items' => __('All Items', 'ximsa'),
                'add_new_item' => __('Add New Mega Menu', 'ximsa'),
                'add_new' => __('Add New', 'ximsa'),
                'new_item' => __('New Mega Menu', 'ximsa'),
                'edit_item' => __('Edit Mega Menu', 'ximsa'),
                'update_item' => __('Update Mega Menu', 'ximsa'),
                'view_item' => __('View Mega Menu', 'ximsa'),
                'search_items' => __('Search Mega Menu', 'ximsa'),
                'not_found' => __('Not found', 'ximsa'),
                'not_found_in_trash' => __('Not found in Trash', 'ximsa'),
                'featured_image' => __('Featured Image', 'ximsa'),
                'set_featured_image' => __('Set featured image', 'ximsa'),
                'remove_featured_image' => __('Remove featured image', 'ximsa'),
                'use_featured_image' => __('Use as featured image', 'ximsa'),
                'insert_into_item' => __('Insert into item', 'ximsa'),
                'uploaded_to_this_item' => __('Uploaded to this item', 'ximsa'),
                'items_list' => __('Items list', 'ximsa'),
                'items_list_navigation' => __('Items list navigation', 'ximsa'),
                'filter_items_list' => __('Filter items list', 'ximsa'),
            );
            $args = array(
                'label' => __('Mega Menu', 'ximsa'),
                'labels' => $labels,
                'supports' => array('title', 'elementor'),
                //                'hierarchical' => false,
//                'public' => true,
//                'show_ui' => true,
//                'show_in_menu' => true,
                'menu_position' => 25,
                'menu_icon' => 'dashicons-menu-alt3',
                //                'show_in_admin_bar' => true,
                'show_in_nav_menus' => false,
                //                'can_export' => true,
//                'has_archive' => false,
//                'exclude_from_search' => true,
//                'publicly_queryable' => false,
//                'rewrite' => false,
                'hierarchical' => false,
                'description' => '',
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => false,
                'show_in_admin_bar' => true,
                //                'menu_position' => null,
//                'menu_icon' => 'dashicons-portfolio',
                'show_in_nav_menus' => false,
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'has_archive' => true,
                'query_var' => true,
                'can_export' => true,
                'capability_type' => 'page',
            );
            register_post_type('th-mega-menu', $args);

        }
    }


    // Custom Fields - Add
    function setup_nav_menu_item($menu_item)
    {
        $menu_item->ct_megaprofile = get_post_meta($menu_item->ID, '_menu_item_ct_megaprofile', true);
        $menu_item->ct_icon = get_post_meta($menu_item->ID, '_menu_item_ct_icon', true);
        $menu_item->ct_onepage = get_post_meta($menu_item->ID, '_menu_item_ct_onepage', true);
        $menu_item->ct_onepage_offset = get_post_meta($menu_item->ID, '_menu_item_ct_onepage_offset', true);
        $menu_item->ct_custom_class = get_post_meta($menu_item->ID, '_menu_item_ct_custom_class', true);
        $menu_item->ct_menu_marker = get_post_meta($menu_item->ID, '_menu_item_ct_menu_marker', true);
        foreach ($this->menu_meta_extra as $key => $fields) {
            $menu_item->$key = get_post_meta($menu_item->ID, '_menu_item_' . $key, true);
        }
        return $menu_item;
    }

    // Custom Fields - Save
    function update_nav_menu_item($menu_id, $menu_item_db_id, $menu_item_data)
    {
        if (isset($_REQUEST['menu-item-ct-megaprofile'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_menu_item_ct_megaprofile', $_REQUEST['menu-item-ct-megaprofile'][$menu_item_db_id]);
        }
        if (isset($_REQUEST['menu-item-ct-icon'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_menu_item_ct_icon', $_REQUEST['menu-item-ct-icon'][$menu_item_db_id]);
        }

        if (isset($_REQUEST['menu-item-ct-onepage'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_menu_item_ct_onepage', $_REQUEST['menu-item-ct-onepage'][$menu_item_db_id]);
        }

        if (isset($_REQUEST['menu-item-ct-onepage-offset'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_menu_item_ct_onepage_offset', $_REQUEST['menu-item-ct-onepage-offset'][$menu_item_db_id]);
        }

        if (isset($_REQUEST['menu-item-ct-custom-class'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_menu_item_ct_custom_class', $_REQUEST['menu-item-ct-custom-class'][$menu_item_db_id]);
        }

        if (isset($_REQUEST['menu-item-ct-menu-marker'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, '_menu_item_ct_menu_marker', $_REQUEST['menu-item-ct-menu-marker'][$menu_item_db_id]);
        }

        foreach ($this->menu_meta_extra as $key => $fields) {
            if (isset($_REQUEST['menu-item-' . $key][$menu_item_db_id])) {
                update_post_meta($menu_item_db_id, '_menu_item_' . $key, $_REQUEST['menu-item-' . $key][$menu_item_db_id]);
            }
        }
    }

    // Custom Backend Walker - Edit
    function edit_nav_menu_walker($walker, $menu_id)
    {
        if (!class_exists('Rs_Mega_Menu_Edit_Walker')) {
            global $extra_menu_custom;
            $extra_menu_custom = $this->menu_meta_extra;
            require_once(BOOKORA_DIR_PATH . '/mega-menu/class-mega-menu-edit.php');
        }

        return 'Rs_Mega_Menu_Edit_Walker';
    }


    /**
     * Get instance of the class
     *
     * @access public
     * @return object this
     */
    public static function get_instance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}