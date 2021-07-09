<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://wplocatepress.com/
 * @since      0.1.0
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Locate_Press
 * @subpackage Locate_Press/includes
 * @author     wplocatepress.com <wplocatepress.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Locatepress_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
