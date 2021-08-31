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

        wp_enqueue_style('ekkocss', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css', array(), $this->version, 'all');

        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

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

        wp_register_script('ekko-lighbox', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js', array(), '', false);

        wp_enqueue_script('ekko-lighbox');

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

        $keyword = isset($_POST['data']['lp_search_keyword']) ? sanitize_text_field($_POST['data']['lp_search_keyword']) : '';
        $listing_type = isset($_POST['data']['lp_search_filter_loctype']) ? sanitize_text_field($_POST['data']['lp_search_filter_loctype']) : '';
        $category_listing = isset($_POST['data']['lp_search_filter_cat']) ? sanitize_text_field($_POST['data']['lp_search_filter_cat']) : '';
        $latti = isset($_POST['data']['lp_location_latitude']) ? sanitize_text_field($_POST['data']['lp_location_latitude']) : '';
        $longi = isset($_POST['data']['lp_location_longitude']) ? sanitize_text_field($_POST['data']['lp_location_longitude']) : '';

        $lp_search_args = array(
            'post_type' => 'map_listing',
            'post_status' => 'publish',

        );
        if (!empty(trim($keyword))) {

            $lp_search_args['s'] = $keyword;
        }

        $tax_query = [];

                if(!empty($latti) && !empty($longi)){

            $lp_search_args['geo_query'] =  array(
                'lat_field' => 'lp_location_latitude',  // this is the name of the meta field storing latitude
                'lng_field' => 'lp_location_longitude', // this is the name of the meta field storing longitude 
                'latitude'  => $latti,    // this is the latitude of the point we are getting distance from
                'longitude' => $longi,   // this is the longitude of the point we are getting distance from
                'distance'  => 10,           // this is the maximum distance to search
                'units'     => 'miles'       // this supports options: miles, mi, kilometers, km
            );
        }

        if (!empty($listing_type)) {
            array_push($tax_query,
                array(
                    'taxonomy' => 'listing_type',
                    'terms' => array($listing_type),
                    'field' => 'term_id',
                )
            );
        }
        if (!empty($category_listing)) {
            array_push($tax_query,
                array(
                    'taxonomy' => 'listing_category',
                    'terms' => array($category_listing),
                    'field' => 'term_id',
                )
            );
        }

        if (!empty($tax_query)) {
            $tax_query['relation'] = 'AND';
            $lp_search_args['tax_query'] = $tax_query;
        }

        $filter_args = apply_filters('locatepress_search_filter_query', $lp_search_args);

        $lp_search_filter_query = new WP_Query($filter_args);
        $marker_data = array();
        $lp_html = '';

        if ($lp_search_filter_query->have_posts()) {
            $i = 0;
            while ($lp_search_filter_query->have_posts()): $lp_search_filter_query->the_post();

                $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                $terms = get_the_terms(get_the_ID(), 'listing_type');
                $term_name = $terms[0]->name;
                $lp_country = get_post_meta(get_the_id(), 'lp_location_country', true);
                $icon_meta = get_term_meta($terms[0]->term_id, 'listing_type-icon', true);
                $coordinates = get_post_meta(get_the_id(), 'lp_location_lat_long', true);
                $explode_coord = explode('/', $coordinates);
                $marker_data[$i] = array(
                    'latitude' => esc_html($explode_coord[0]),
                    'longitude' => esc_html($explode_coord[1]),
                    'p_id' => esc_html(get_the_id()),
                    'marker_icon' => esc_url(wp_get_attachment_url($icon_meta)),
                    'title' => esc_html(get_the_title()),
                    'permalink' => esc_url(get_the_permalink()),
                    'listing_type_id' => esc_html($terms[0]->term_id),
                    'featured_image' => esc_url($featured_img_url),
                    'location' => esc_html($lp_country),
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
            'post__in' => $idArr,
            'post_type' => array('map_listing'),
            'post_status' => array('publish'),
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

    /**
     * gets the images uploaded in gallery metabox of listing post type
     *
     * @since      1.0.0
     * @access public
     * @static
     * @return html
     */

    public static function locatepress_single_listing_gallery($post_id)
    {
        $image_gallery_data = get_post_meta($post_id, 'image_gallery_data', true);
        $html = '';
        if ($image_gallery_data['img_url'] != '') {
            $html = '<div class="container">';
            $html .= '<div class="row">';
            for ($i = 0; $i < count($image_gallery_data['img_url']); $i++) {

                if ($i % 3 === 0 && $i > 0) {
                    $html .= '</div><div class="row">';

                }
                $html .= '<a href="' . esc_url($image_gallery_data['img_url'][$i]) . '" data-toggle="lightbox" data-gallery="gallery" class="col-md-4">';
                $html .= '<img src="' . esc_url($image_gallery_data['img_url'][$i]) . '" class="img-fluid rounded"></a>';

            }

            $html .= '</div>';
            $html .= '</div>';

        }

        return apply_filters('locatepress_single_listing_gallery', $html);
        ;
    }

    /**
     * gets the contact no of listing post type
     *
     * @since      1.0.0
     * @access public
     * @static
     * @return html
     */

    public static function locatepress_single_contact_no($post_id)
    {
        $locatepress_contact_no = get_post_meta($post_id, 'locatepress_contact_no', true);
        $html ='';
        if ($locatepress_contact_no != '') {
            $html  = '<p class="lp-contact">';
            $html .= '<i class="fa fa-phone" aria-hidden="true"></i>';
            $html .= esc_html($locatepress_contact_no);
            $html .= '</p>';
        }
        return  apply_filters('locatepress_single_contact_no', $html);;
    }

    /**
     * gets the business hour of listing post type
     *
     * @since      1.0.0
     * @access public
     * @static
     * @return html
     */

    public static function locatepress_single_business_hour($post_id)
    {
        $locatepress_business_hour = get_post_meta($post_id, 'locatepress_business_hour', true);
        $html = '';
        if ($locatepress_business_hour != '') {
            $html  = '<div class="lp-business-hour">';
            $html .= '<p class="lp-business-hour-title">' . __('Business Hour', 'locatepress') . '</p>';
            $html .= $locatepress_business_hour;
            $html .= '</div>';
        }
        return apply_filters('locatepress_single_business_hour', $html);
    }

    /**
     * gets the social profile
     * @since      1.0.0
     * @access public
     * @static
     * @return html
     */

    public static function locatepress_single_social_profile($post_id)
    {
        $locatepress_yt = get_post_meta($post_id, 'locatepress_yt-url', true);

        $locatepress_ig = get_post_meta($post_id, 'locatepress_ig-url', true);

        $locatepress_pin = get_post_meta($post_id, 'locatepress_pin-url', true);

        $locatepress_fb = get_post_meta($post_id, 'locatepress_fb-url', true);

        $locatepress_twir = get_post_meta($post_id, 'locatepress_twir-url', true);

        $html ='';

            $html = '<div class="lp-social-profile">';
            $html .= '<p class="lp-social-profile-title">' . __('Social Profile', 'locatepress') . '</p>';

            if ($locatepress_fb != ''){
                $html .= '<a href="'.esc_url($locatepress_fb).'" class="fa fa-facebook"></a>';
            }

            if ($locatepress_ig != ''){
                $html .= '<a href="'.esc_url($locatepress_ig).'" class="fa fa-instagram"></a>';
            }

            if ($locatepress_pin != ''){
                $html .= '<a href="'.esc_url($locatepress_pin).'" class="fa fa-pinterest"></a>';
            }

            if ($locatepress_yt != ''){
                $html .= '<a href="'.esc_url($locatepress_yt).'" class="fa fa-youtube"></a>';
            }
            if ($locatepress_twir != ''){
                $html .= '<a href="'.esc_url($locatepress_twir).'" class="fa fa-twitter"></a>';
            }
            $html .= '</div>';
    
            return apply_filters('locatepress_single_social_profile', $html);

    }

}
