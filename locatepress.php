<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wplocatepress.com/
 * @since             0.1.0
 * @package           Locatepress
 *
 * @wordpress-plugin
 * Plugin Name:       Locatepress
 * Plugin URI:        http://wplocatepress.com/locatepress/
 * Description:       Locatepress is a wordpress plugin used to filter and track the listings with google maps.
 * Version:           0.1.0
 * Author:            wplocatepress.com
 * Author URI:        http://wplocatepress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       locatepress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LOCATEPRESS_VERSION', '0.1.0');

define('LOCATEPRESS_SETTINGS_URL', get_admin_url() . "edit.php?post_type=map_listing&page=locatepress_dashboard");

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-locatepress-activator.php
 */
function activate_locatepress()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-locatepress-activator.php';
    Locatepress_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-locatepress-deactivator.php
 */
function deactivate_locatepress()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-locatepress-deactivator.php';
    Locatepress_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_locatepress');
register_deactivation_hook(__FILE__, 'deactivate_locatepress');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

//require plugin_dir_path( __FILE__ ) . 'includes/class-locatepress.php';
require plugin_dir_path(__FILE__) . 'includes/class-locatepress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_locatepress()
{

    $plugin = new Locatepress();
    $plugin->run();

}
run_locatepress();

register_activation_hook(__FILE__, 'locatepress_activate');

add_action('admin_init', 'locatepress_redirect');

function locatepress_activate()
{
    add_option('locatepress_do_activation_redirect', true);
}

function locatepress_redirect()
{
    if (get_option('locatepress_do_activation_redirect', false)) {
        delete_option('locatepress_do_activation_redirect');
        wp_redirect(LOCATEPRESS_SETTINGS_URL);
    }
}

function locatepress_add_settings_link($links)
{
    $url = LOCATEPRESS_SETTINGS_URL;
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'locatepress') . '</a>';
    $links[] = $settings_link;
    return $links;
}
$plugin = plugin_basename(__FILE__);

add_filter("plugin_action_links_$plugin", 'locatepress_add_settings_link');
