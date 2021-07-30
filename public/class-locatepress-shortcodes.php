<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Shortcodes
{

    /**
     * The ID of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * The filter fields of search bar
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */

    private $fields;
    /**
     * The Settings from dashboard
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */

    private $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.1.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = get_option('locate_press_set');
        $lp_options = get_option('locate_press_set');

        if (isset($lp_options['lp_map_js'])) {

            $map_type = $lp_options['lp_map_js'];

        } else {

            $map_type = 'google-map';
        }
        $this->fields = array(
            'keyword_filter' => array(
                'type' => 'text',
                'title' => __('Keyword Search', 'locatepress'),
                'placeholder' => __('Search...', 'locatepress'),
                'name' => 'lp_search_keyword',
                'settings' => 'lp_ky_search',
                'class' => 'lp-input-keyword',

            ),
            'location_search' =>
            $map_type == 'google-map' ?
            array(
                'type' => 'text',
                'title' => __('Location Search', 'locatepress'),
                'placeholder' => __('Location Search', 'locatepress'),
                'name' => 'lp_search_filter_loc',
                'settings' => 'lp_location_search',
                'class' => 'lp-loc-search',
            ) :
            array(
                'type' => 'div',
                'title' => __('Location Search', 'locatepress'),
                'placeholder' => __('Location Search', 'locatepress'),
                'name' => 'lp_search_filter_loc',
                'settings' => 'lp_location_search',
                'class' => 'lp-loc-search',
            ),
            'location_types' => array(
                'type' => 'select',
                'title' => __('Location Types', 'locatepress'),
                'placeholder' => __('All Location Types', 'locatepress'),
                'name' => 'lp_search_filter_loctype',
                'settings' => 'lp_locationtype_search',
                'class' => 'lp-search-filter-loc',
                'tax_slug' => 'location_type',
            ),
            'category' => array(

                'type' => 'select',
                'title' => __('Category', 'locatepress'),
                'placeholder' => __('All categories', 'locatepress'),
                'name' => 'lp_search_filter_cat',
                'settings' => 'lp_locationtype_search',
                'class' => 'lp-search-filter-cat',
                'tax_slug' => 'listing_category',
            ),
            'resetbutton' => array(
                'type' => 'button',
                'placeholder' => __('Reset', 'locatepress'),
                'settings' => 'lp_filter_reset-btn',
                'class' => 'lp-filter-form-reset',

            ),

            'filterbutton' => array(
                'type' => 'submit',
                'placeholder' => __('Filter', 'locatepress'),
                'settings' => '',
                'name' => 'lp_filter_submit',
                'class' => 'lp-filter-form-reset',
                'value' => '',
            ),
        );

        add_filter('locatepress_searchbar_filters', array($this, 'locatepress_remove_filter_button'));
    }

    //removes filter button from filter page
    public function locatepress_remove_filter_button($arr)
    {

        if (is_page($this->settings['lp_filter_page']) && !empty($this->settings['lp_filter_page'])) {
            unset($arr['filterbutton']);

        }
        return $arr;
    }
    public function locatepress_taxonomy_data($catSlug)
    {

        $tax = get_terms(array(
            'taxonomy' => $catSlug,
            'hide_empty' => true,
            'exclude' => 1,
        ));

        return $tax;
    }

//map display shortcode
    public function locatepress_map_shortcode($atts)
    {
        ob_start();

        extract(shortcode_atts(array(
            'width' => '100%',
            'height' => '400px',

        ), $atts, 'locatepress_map'));

        do_action('locatepress_before_map');

        echo apply_filters('locatepress_before_map', '<div class="lp-map-container">');

        echo '<div class="lp-display-map" id="lp-display-map" style="height:' . $height . ';width:' . $width . ';">';
        echo '</div>';

        echo apply_filters('locatepress_after_map', '</div>');

        do_action('locatepress_after_map');

        return ob_get_clean();
    }

/**
 * Functionality : returns the listing of locations and shops according to parameters provided while serach or in shortcode attribute
 * Parameters      : $atts(attributes of shortcode(location_types, categories, count, columns))
 * returns          : list of queried location and shops
 * @since 0.1.0
 */
    public function locatepress_listings_shortcode($atts)
    {

        ob_start();
        extract(shortcode_atts(array(
            'location_types' => '',
            'categories' => '',
            'count' => '-1',
            'columns' => '4',
        ), $atts, 'locatepress_listings'));

        $tax_query1 = array(
            'relation' => 'AND',
        );
        $tax_query2 = array(
            'relation' => 'AND',
        );

        if (!empty($location_types)) {
            $location_terms_array = explode(',', $location_types);
            array_push($tax_query1, array(
                'taxonomy' => 'location_type',
                'terms' => $location_terms_array,
                'field' => 'term_id',
                'operator' => 'IN',
                'include_children' => false,
            ));
        }
        if (!empty($categories)) {
            $category_terms_array = explode(',', $categories);
            array_push($tax_query1, array(
                'taxonomy' => 'listing_category',
                'terms' => $category_terms_array,
                'field' => 'term_id',
                'operator' => 'IN',
                'include_children' => false,
            ));
        }

        $listing_args = array(
            'post_type' => array('map_listing'),
            'post_status' => array('publish'),
            'posts_per_page' => $count,
            'tax_query' => $tax_query1,

        );
        if (!empty($_GET['lp_search_filter_cat']) && $_GET['lp_search_filter_cat'] != 'All') {
            $cat = $_GET['lp_search_filter_cat'];
            array_push($tax_query2, array(
                'taxonomy' => 'listing_category',
                'terms' => $cat,
                'field' => 'term_id',
                'operator' => 'IN',
                'include_children' => false,
            ));

        }
        if (!empty($_GET['lp_search_filter_loctype']) && $_GET['lp_search_filter_loctype'] != 'All') {
            $loctype = $_GET['lp_search_filter_loctype'];
            array_push($tax_query2, array(
                'taxonomy' => 'location_type',
                'terms' => $loctype,
                'field' => 'term_id',
                'operator' => 'IN',
                'include_children' => false,
            ));
        }

        $listing_args_2 = array(
            'post_type' => array('map_listing'),
            'post_status' => array('publish'),
            'posts_per_page' => $count,
            'tax_query' => $tax_query2,

        );

        if (!empty($_GET['lp_search_filter_loctype']) || !empty($_GET['lp_search_filter_cat'])) {
            $listings_query = new WP_Query($listing_args_2);

        } else {
            $listings_query = new WP_Query($listing_args);

        }

        if ($listings_query->have_posts()) {
            do_action('locatepress_before_listing');
            echo apply_filters('locatepress_listing_starts', $this->locatepress_start(), 10, 9);
            echo apply_filters('before_listing_loop_start', '<div class="lp-listing-container"><div class="lp-display-listing">');

            while ($listings_query->have_posts()): $listings_query->the_post();
                include plugin_dir_path(__FILE__) . 'partials/locatepress-listings.php';
            endwhile;
            wp_reset_postdata();
            echo apply_filters('after_listing_loop_ends', '</div></div>');
            echo apply_filters('locatepress_listing_ends', $this->locatepress_form_end(), 10, 9);

            do_action('locatepress_after_listing');

        }
        return ob_get_clean();

    }

/**
 * Functionality : dispays the search bar
 * Parameters      : $atts(attributes of shortcode(map, listing))
 * returns          : search bar with map and lisitng of set true
 * @since 0.1.0
 */
    public function locatepress_search_bar_shortcode($atts)
    {
        ob_start();
        extract(shortcode_atts(array(
            'map' => 'false',
            'listing' => 'false',
        ), $atts, 'locatepress_filters'));

        $filter_fields = apply_filters('locatepress_searchbar_filters', $this->fields, 10, 9);

        echo apply_filters('locatepress_filter_start', $this->locatepress_start(), 10, 9);

        echo apply_filters('locatepress_before_form_start', $this->locatepress_before_form_start(), 10, 9);
        echo '<div class="lp-search-filter">';
        echo apply_filters('locatepress_form_start', $this->locatepress_form_start(), 10, 9);
        echo '<ul class="lp-search-filter-elm">';

        foreach ($filter_fields as $key => $value) {

            if (!isset($this->settings[$value['settings']])) {
                do_action('locatepress_before_' . $key);
                echo '<li class="lp_' . $key . '">';
                if (!empty($value['title'])) {
                    echo '<h3>' . $value['title'] . '</h3>';

                }

                if (!empty($value['type'])) {

                    switch ($value['type']) {
                        case 'text':
                            echo '<input placeholder="' . $value['placeholder'] . '" name="' . $value['name'] . '" class="' . $value['class'] . '" type="text" autocomplete="off" value="' . esc_html($this->locatepress_post_values($value['name'])) . '">';

                            break;
                        case 'div':
                            echo '<div id="' . $value['class'] . '"></div>';
                            echo '<pre id="result"></pre>';
                            break;

                        case 'button':

                            echo '<input type="button" id="lp-' . $key . '"  class="' . $value['class'] . '"  value="' . $value['placeholder'] . '">';

                            break;

                        case 'submit':

                            echo '<input type="submit" id="lp-' . $key . '" name="' . $value['name'] . '"  class="' . $value['class'] . '"  value="' . $value['placeholder'] . '">';

                            break;

                        case 'select':
                            $taxitems = $this->locatepress_taxonomy_data($value['tax_slug']);
                            echo '<select class="' . $value['class'] . ' select-css" name="' . $value['name'] . '">';

                            echo '<option value="All" >' . $value['placeholder'] . '</option>';

                            foreach ($taxitems as $t) {

                                if (isset($_GET[$value['name']]) && ($_GET[$value['name']] == $t->term_id)) {

                                    echo '<option value="' . $t->term_id . '" selected>' . esc_html($t->name) . '(' . esc_html($t->count) . ')</option>';

                                    continue;
                                }
                                echo '<option  value="' . $t->term_id . '">' . esc_html($t->name) . '(' . esc_html($t->count) . ')</option>';

                            }

                            echo '</select>';
                            break;

                        default:
                            echo '<p>' . $value['type'] . __('is not supported', 'locatepress') . ' </p>';
                            break;
                    }
                }

                do_action('locatepress_after_' . $key);
                echo '</li>';
            }

        }

        echo '</ul>';

        echo apply_filters('locatepress_form_end', $this->locatepress_form_end(), 10, 9);

        echo '</div>';
        if ($map == 'true') {
            echo do_shortcode('[locatepress_map]');
        }

        echo apply_filters('locatepress_after_form_end', $this->locatepress_after_form_end(), 10, 9);

        if ($listing == 'true') {
            if (isset($_GET['lp_search_filter_loctype'])) {
                $location_type = $_GET['lp_search_filter_loctype'];
            } else {
                $location_type = '';
            }

            if (isset($_GET['lp_search_filter_cat'])) {
                $category_type = $_GET['lp_search_filter_cat'];
            } else {
                $category_type = '';
            }

            echo do_shortcode('[locatepress_listing location_types="' . $location_type . '" categories="' . $category_type . '" count="-1"]');

        }

        echo apply_filters('locatepress_filter_end', $this->locatepress_end(), 10, 9);

        return ob_get_clean();

    }

    //container berfore form starts
    public function locatepress_before_form_start()
    {
        if (isset($this->settings['lp_search_disp'])) {
            $searchbar_align = $this->settings['lp_search_disp'];
        } else {
            $searchbar_align = 'top';
        }
        echo '<div class="lp-wrap search-align-' . $searchbar_align . '" >';

    }
    //container after form ends
    public function locatepress_after_form_end()
    {
        echo '</div>';

    }
    //Search Bar Form Starts
    public function locatepress_form_start()
    {

        return '<form  class="lp-search-filter-form" id="lp-search-filter-form" name="lp_search_filter"  method="GET" action="' . esc_url(get_permalink($this->settings['lp_filter_page'])) . '" >';

    }

    //Search Bar Form Ends

    public function locatepress_form_end()
    {

        return '</form>';
    }

    //container Start
    public function locatepress_start()
    {
        $s_html = '<div class="lp-row">';
        $s_html .= '<div class="lp-container">';
        return $s_html;

    }

    //container end
    public function locatepress_end()
    {
        $e_html = '</div>';
        $e_html .= '</div>';
        return $e_html;

    }

    public function locatepress_post_values($name)
    {

        if (isset($_GET[$name])) {
            return $_GET[$name];
        } else {

            return false;
        }

    }

}
