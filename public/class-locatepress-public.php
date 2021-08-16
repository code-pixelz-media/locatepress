<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wplocatepress.com/
 * @since      1.0.0
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Locate_Press
 * @subpackage Locate_Press/public
 * @author     wplocatepress.com <wplocatepress.com>
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    /**
     * Sets Settings Option From Dashboard
     *
     * @since    1.0.0
     */

    private $settings;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = get_option('locate_press_set');

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function locatepress_public_enqueue_styles()
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/locatepress-public.css', array(), $this->version, 'all');

        wp_enqueue_style('bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function locatpress_public_enqueue_scripts()
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

        $locate_press_options = get_option('locate_press_set');

        $locate_press_api_key = $locate_press_options['lp_map_api_key'];

        wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?&key=' . $locate_press_api_key . '&libraries=places', array(), '', false);

        wp_enqueue_script('googlemaps');

        wp_register_script('googlemaps-polyfill', 'https://polyfill.io/v3/polyfill.min.js?features=default', array(), '', false);

        wp_enqueue_script('googlemaps-polyfill');

        wp_register_script('googlemaps-cluster', 'https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js', array(), '', false);

        wp_enqueue_script('googlemaps-cluster');

        wp_enqueue_script('jquery');

        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/locatepress-public.js', array('jquery'), $this->version, true);

        wp_localize_script($this->plugin_name, 'lp_settings', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'map' => $this->settings,
        ));
        wp_enqueue_script($this->plugin_name);

        wp_enqueue_script('bootstrapjs', plugin_dir_url(__FILE__) . 'js/bootstrap.js', array('jquery'), $this->version, false);

    }

    public function locatepress_ajax_search_filter()
    {

        $keyword            = isset( $_POST['data']['lp_search_keyword']) ? sanitize_text_field( $_POST['data']['lp_search_keyword'] ) : '';
        $listing_type       = isset( $_POST['data']['lp_search_filter_loctype']) ? sanitize_text_field( $_POST['data']['lp_search_filter_loctype'] ): '';
        $category_listing   = isset( $_POST['data']['lp_search_filter_cat']) ? sanitize_text_field( $_POST['data']['lp_search_filter_cat'] ) : '';

        $lp_search_args = array(
            'post_type'     => 'map_listing',
            'post_status'   => 'publish',
            
        );
        if (!empty(trim($keyword))) {

            $lp_search_args['s'] = $keyword;
        }

        $tax_query = [];

        if (!empty($listing_type)){
            array_push($tax_query,
            array(
                'taxonomy' => 'listing_type',
                'terms' => array($listing_type),
                'field' => 'term_id'
            )
            );
        }
        if (!empty($category_listing)){
            array_push($tax_query,
            array(
                'taxonomy'  => 'listing_category',
                'terms'     => array($category_listing),
                'field'     => 'term_id'
            )
            );
        }

        if ( ! empty( $tax_query ) ) {
			$tax_query['relation']          = 'AND';
			$lp_search_args['tax_query']    = $tax_query;
		}

        
        $filter_args = apply_filters('locatepress_search_filter_query', $lp_search_args);

        $lp_search_filter_query = new WP_Query($filter_args);
        $marker_data = array();
        $lp_html = '';

        if ($lp_search_filter_query->have_posts()) {
            $i = 0;
            while ($lp_search_filter_query->have_posts()): $lp_search_filter_query->the_post();

                $featured_img_url   = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                $terms              = get_the_terms(get_the_ID(), 'listing_type');
                $term_name          = $terms[0]->name;
                $lp_country         = get_post_meta(get_the_id(), 'lp_location_country', true);
                $icon_meta          = get_term_meta($terms[0]->term_id, 'listing_type-icon', true);
                $coordinates        = get_post_meta(get_the_id(), 'lp_location_lat_long', true);
                $explode_coord      = explode('/', $coordinates);
                $marker_data[$i]    = array(
                    'latitude'          => esc_html($explode_coord[0]),
                    'longitude'         => esc_html($explode_coord[1]),
                    'p_id'              => esc_html(get_the_id()),
                    'marker_icon'       => esc_url(wp_get_attachment_url($icon_meta)),
                    'title'             => esc_html(get_the_title()),
                    'permalink'         => esc_url(get_the_permalink()),
                    'listing_type_id'   => esc_html($terms[0]->term_id),
                    'featured_image'    => esc_url($featured_img_url),
                    'location'          => esc_html($lp_country),
                );
                ob_start();
                include plugin_dir_path(__FILE__) . 'partials/locatepress-listings.php';
                $lp_html .= ob_get_clean();

                $i++;
            endwhile;

            wp_reset_postdata();

            $output_result = array(
                'success' => true,
                'listings' => $lp_html,
                'marker_data' => $marker_data,

            );

        } else {
            $output_result = array(
                'success' => false,
                'listings' => '<p>' . __('Listing Not Found', 'locatepress') . '</p>',
                'marker_data' => $marker_data,

            );

        }
        echo json_encode($output_result);
        wp_die();

    }

    //Display Markers That Are Visible
    public function locatepress_listings_visible_markers()
    {

        $idArr = array_unique($_POST['data']);

        $visbleargs = array(
            'post__in'      => $idArr,
            'post_type'     => array('map_listing'),
            'post_status'   => array('publish'),
        );

        // The Query
        $lp_visble_list = new WP_Query($visbleargs);

        if ($lp_visble_list->have_posts()) {
            $v_html = '';
            while ($lp_visble_list->have_posts()): $lp_visble_list->the_post();
                ob_start();

                include plugin_dir_path(__FILE__) . 'partials/locatepress-listings.php';

                $v_html .= ob_get_clean();

            endwhile;
            wp_reset_postdata();

            $results = array(
                'success' => true,
                'html' => $v_html,
            );

        } else {

            $results = array(
                'success' => false,
                'html' => '<p>' . __("Listings Not Found", "locatepress") . ' </p>',

            );

        }

        echo json_encode($results);

        wp_die();

    }

    public function locatepress_load_single_template()
    {

        global $post;

        $file = dirname(__FILE__) . '/single-' . $post->post_type . '.php';

        if (file_exists($file)) {
            $single_template = $file;
        }

        return $single_template;
    }

}
