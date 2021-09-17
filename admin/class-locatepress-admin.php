<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wplocatepress.com/
 * @since      1.0.0
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/admin
 * @author     wplocatepress.com <wplocatepress.com>
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name  = $plugin_name;
        $this->version      = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function locatepress_admin_enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Locate_Press_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Locate_Press_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //admin css from ddf plugin
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/locatepress-admin.css', array(), $this->version, 'all');

        // Enqueued for font awesome
        
        wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function locatepress_admin_enqueue_scripts()
    {

        /**
         * An instance of this class should be passed to the run() function
         * defined in Locate_Press_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Locate_Press_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $locate_press_options = get_option('locate_press_set');

        $locate_press_api_key = isset($locate_press_options['lp_map_api_key']) ? esc_html($locate_press_options['lp_map_api_key'] ) : '' ;
        

        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/locatepress-admin.js', array('jquery'), $this->version, true);

        wp_enqueue_script('jquery-ui-accordion');

        wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?&key=' . $locate_press_api_key . '&libraries=places', array(), '', false);
        
        wp_enqueue_script('fa-js', plugin_dir_url(__FILE__) . 'js/fontawesome-kit.js', array(), '', false);

        wp_enqueue_media();

        wp_register_script('map-script', plugin_dir_url(__FILE__) . 'js/locatepress-map.js', array('jquery'), $this->version, true);

        wp_register_script('gallery-script', plugin_dir_url(__FILE__) . 'js/locatepress-gallery.js', array('jquery'), $this->version, true);

        wp_register_script('logo-script', plugin_dir_url(__FILE__) . 'js/locatepress-listing-logo.js', array('jquery'), $this->version, true);

        wp_enqueue_script('jquery-ui-tabs');

        wp_enqueue_script('admin-script', plugin_dir_url(__FILE__) . 'js/admin-script.js');

    }

    public function locatpress_add_plugin_page_settings_link()
    {
        $url            = get_admin_url() . "options-general.php?page=my-plugin";
        $settings_link  = '<a href="' . esc_url ($url) . '">' . __('Settings', 'textdomain') . '</a>';
        $links[]        = $settings_link;
        return $links;
    }

    public function locatepress_add_sidebar() {
        $args = array(
          'name'          => __('Locatepress Single Page Sidebar','locatepress'),
          'id'            => 'locatepress-sidebar',
          'description'   => __( 'sidebar displayed in locatepress plugins single page' ),
          'before_widget' => '<div id="%1$s" class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<h3 class="widget-title">',
          'after_title'   => '</h3>',
        );
      
        register_sidebar( $args );
      
      }

}
