<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wplocatepress.com/
 * @since      1.0.0
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Locate_Press
 * @subpackage Locate_Press/includes
 * @author     wplocatepress.com <wplocatepress.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Locatepress_Activator {

	/**
	 * Triggered during the plugin activation.
	 * @since    1.0.0
	 */


	public static function activate() {

		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-cpt.php';
		
    	$locatepress_post_types = new Locatepress_Register_Cpt();

    	$locatepress_post_types->locatepress_create_custom_post_type();

    	flush_rewrite_rules();

	}

}
