<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Settings
{

    public function locatepress_dashboard_init()
    {

        add_action('admin_menu', array($this, 'locatepress_add_admin_menu'));
        add_action('admin_init', array($this, 'locatepress_init_settings'));

    }

    public function locatepress_add_admin_menu()
    {

        add_submenu_page(
            'edit.php?post_type=map_listing',
            esc_html__('Locatepress Settings', 'locatepress'),
            esc_html__('Settings', 'locatepress'),
            'manage_options',
            'locatepress_dashboard',
            array($this, 'locatepress_dashboard_display')
        );

    }

    public function locatepress_init_settings()
    {

        register_setting(
            'locate_press_set_group',
            'locate_press_set'
        );

        add_settings_section(
            'locate_press_set_section',
            '',
            false,
            'locate_press_set'
        );
    }

    public function locatepress_dashboard_display()
    {

        // Check required user capability
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'locatepress'));
        }

        // Admin Page Layout
        echo '<div class="cpm-plugin-wrap">' . "\n";

        //get Header of plugin
        require_once plugin_dir_path(__FILE__) . 'partials/locatepress-admin-header.php';
        echo '<div class="body-wrap">'; //body wrap start
        echo '<div id="tabs-wrap" class="tabs-wrap">'; //tabs-wrap start
        echo '<ul class="tab-menu">';
        echo '<li class="nav-tab "><a href="#general" class="dashicons-before dashicons-editor-alignleft">' . __('General', 'locatepress') . '</a></li>';
        echo '<li class="nav-tab"><a href="#search-bar" class="dashicons-before dashicons-admin-generic">' . __('Search Bar', 'locatepress') . '</a></li>';
        echo '<li class="nav-tab"><a href="#map-settings" class="dashicons-before dashicons-admin-settings">' . __('Map Settings', 'locatepress') . '</a></li>';
        echo '</ul>'; //tab-menu ends
        echo '<div class="tab-content">'; //tab content start
        echo '<form action="options.php" method="post" id="save-settings">' . "\n";
        settings_fields('locate_press_set_group');

        require_once plugin_dir_path(__FILE__) . 'partials/locatepress-admin-display.php';

        echo '</form>' . "\n";
        echo '</div>'; //tab content end
        echo '</div>'; //tabs-wrap ends
        echo '</div>'; //body wrap end

        require_once plugin_dir_path(__FILE__) . 'partials/locatepress-addons.php';
        require_once plugin_dir_path(__FILE__) . 'partials/locatepress-admin-footer.php';
        
        //get footer of plugin
        echo '</div>' . "\n"; //cpm-plugin-wrap ends

    }
}
