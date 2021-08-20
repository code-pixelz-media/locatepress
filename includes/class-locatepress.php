<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wplocatepress.com/
 * @since      1.0.0
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
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

class Locatepress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Locatepress_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LOCATEPRESS_VERSION' ) ) {
			$this->version = LOCATEPRESS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'locatepress';

		$this->locatepress_load_dependencies();
		$this->locatepress_set_locale();
		$this->locatepress_define_admin_hooks();
		$this->locatepress_define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Locatepress_Loader. Orchestrates the hooks of the plugin.
	 * - Locatepress_i18n. Defines internationalization functionality.
	 * - Locatepress_Admin. Defines all hooks for the admin area.
	 * - Locatepress_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function locatepress_load_dependencies() {


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locatepress-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locatepress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-locatepress-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-locatepress-public.php';
		/**
		 * The class responsible for defining all shortcodes that occur in the public-facing side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-locatepress-shortcodes.php';

		/**
		 * The class responsible for defining custom post type on admin
		 * side of the site.
		 */ 

		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-cpt.php';

		/**
		 * The class responsible for registering metabox for CPT Map Listing
		 * 
		 */

		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-meta-map.php';
		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-meta-listing-details.php';
		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-meta-gallery.php';
		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-meta-logo.php';




		/**
		 * The class responsible for registering taxonomies
		 * 
		 */
		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-term-meta.php';

		/**
		 * The class responsible for plugin settings
		 * 
		 */

		require_once plugin_dir_path(dirname(__FILE__)) .'admin/class-locatepress-settings.php';

		

		$this->loader = new Locatepress_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Locatepress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function locatepress_set_locale() {

		$plugin_i18n = new Locatepress_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function locatepress_define_admin_hooks() {

		$plugin_admin = new Locatepress_Admin( $this->locatepress_get_plugin_name(), $this->locatepress_get_version() );

		$plugin_cpt   = new Locatepress_Register_Cpt($this->locatepress_get_plugin_name() , $this->locatepress_get_version());

		$plugin_meta_map  =  new Locatepress_Register_Metabox_Map($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$plugin_meta_lisitng_details  =  new Locatepress_Register_Metabox_Listing_Details($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$plugin_meta_gallery  =  new Locatepress_Register_Metabox_Gallery($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$plugin_meta_logo  =  new Locatepress_Register_Metabox_Logo($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$plugin_settings = new Locatepress_Settings($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$plugin_term_meta = new Locatepress_Term_Meta($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'locatepress_admin_enqueue_styles' );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'locatepress_admin_enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_cpt, 'locatepress_create_custom_post_type', 10 );

		$this->loader->add_action( 'load-post.php', $plugin_meta_map, 'locatepress_init_metabox' );

		$this->loader->add_action( 'load-post-new.php', $plugin_meta_map, 'locatepress_init_metabox' );

		$this->loader->add_action( 'load-post.php', $plugin_meta_lisitng_details, 'init_metabox' );

		$this->loader->add_action( 'load-post-new.php', $plugin_meta_lisitng_details, 'init_metabox' );

		$this->loader->add_action( 'load-post.php', $plugin_meta_gallery, 'init_metabox' );

		$this->loader->add_action( 'load-post-new.php', $plugin_meta_gallery, 'init_metabox' );

		$this->loader->add_action( 'load-post.php', $plugin_meta_logo, 'init_metabox' );

		$this->loader->add_action( 'load-post-new.php', $plugin_meta_logo, 'init_metabox' );

		$this->loader->add_action( 'init', $plugin_term_meta, 'locatepress_initialize_term_meta' );
		
		$this->loader->add_action( 'init', $plugin_settings, 'locatepress_dashboard_init' );

		$this->loader->add_filter('plugin_action_links_'.plugin_basename(__FILE__),$plugin_admin,'locatpress_add_plugin_page_settings_link');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function locatepress_define_public_hooks() {

		$plugin_public = new Locatepress_Public( $this->locatepress_get_plugin_name(), $this->locatepress_get_version() );

		$plugin_shortcodes = new Locatepress_Shortcodes($this->locatepress_get_plugin_name(),$this->locatepress_get_version());

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'locatepress_public_enqueue_styles' );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'locatpress_public_enqueue_scripts' );

		$this->loader->add_shortcode( 'locatepress_search_bar', $plugin_shortcodes, 'locatepress_search_bar_shortcode' );

		$this->loader->add_shortcode('locatepress_map',$plugin_shortcodes,'locatepress_map_shortcode');

		$this->loader->add_shortcode( 'locatepress_listing', $plugin_shortcodes, 'locatepress_listings_shortcode' );

		$this->loader->add_filter('single_template', $plugin_public, 'locatepress_load_single_template');

		$this->loader->add_action('wp_ajax_locatepress_ajax_search_filter',$plugin_public,'locatepress_ajax_search_filter');

		$this->loader->add_action('wp_ajax_nopriv_locatepress_ajax_search_filter',$plugin_public,'locatepress_ajax_search_filter');

		$this->loader->add_action('wp_ajax_locatepress_listings_visible_markers',$plugin_public,'locatepress_listings_visible_markers');

		$this->loader->add_action('wp_ajax_nopriv_locatepress_listings_visible_markers',$plugin_public,'locatepress_listings_visible_markers');
	
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function locatepress_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Locatepress_Loader    Orchestrates the hooks of the plugin.
	 */
	public function locatepress_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function locatepress_get_version() {
		return $this->version;
	}

}
