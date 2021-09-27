<?php
    // If this file is called directly, abort.

    if (!defined('WPINC')) {
        die;
    }
?>
<div class="addon-wrap">
    <div class="row">
        <div class="addon-logo-wrap col-md-6">
            <div class="title">
                <h3 class="addon-wrap-title"><?php _e('Locatepress Addons', 'locatepress');?></h3>
                <!-- <p><a href="http://wplocatepress.com/"><?php _e('Locatepress', 'locatepress');?></a></p> -->
            </div>
        </div>
    </div>
    
    <div class="row">
        <?php
        $url = esc_url('https://wplocatepress.com/locatepress-addons.json');

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return false; // Bail early
        }

        $body = wp_remote_retrieve_body($response);
        $addons = json_decode($body, true);

        foreach ($addons as $addon) {
            $title          = $addon['name'];
            $desc           = $addon['about'];
            $image          = $addon['icon'];
            $version        = $addon['version'];
            $type           = $addon['type'];
            $slug           = $addon['slug'];
            $date           = $addon['date']; //date created
            $price          = $addon['price'];
            $pluginPath     = $slug . '/' . $slug . '.php';
            $pathpluginurl  = WP_PLUGIN_DIR . '/' . $pluginPath;
            
            //check if plugin is downloaded or not
            if (file_exists($pathpluginurl)) {
                if (is_plugin_active($pluginPath)) {
                    $buttonText = __('Activated', 'locatepress');
                    $url        = "#";
                    $btnColor   = 'btn-grey';

                } else {
                    $btnColor   = 'btn-green';
                    $buttonText = __('Activate', 'locatepress');
                    $url        = admin_url('plugins.php');

                }

            } else {
                if ($type == "premium") {
                    $btnColor   = 'btn-red';
                    $buttonText = sprintf(__('Buy Now : %s', 'locatepress'), $price);
                    $url        = $addon['url'];
                } else {

                    $btnColor   = 'btn-red';
                    $buttonText = __('Download', 'locatepress');
                    $url        = $addon['url'];
                }

            }

            if ($type == 'free') {
                $ribbontext = __('Free', 'locatepress');
            } else {
                $ribbontext = sprintf(__('Premium (%s)', 'locatepress'), $price);
            }

            echo '<div class="singleaddon-wrap col-md-3">';
            echo '<div class="ribbon ribbon-top-right"><span>' . esc_html($ribbontext) . '</span></div>';
            echo '<div class="addons-wrap-inner">';
            echo '<img src="' . esc_url($image) . '"><div class="addons-content">';
            echo '<h4 class= "addon-title">' . esc_html__($title, 'locatepress') . '</h4>';
            echo '<a href="' . esc_url($url) . '" class="addon-detail-button">' . __('Addon Details', 'locatepress') . '<a/>';
            echo '<p class= "addon-version"><strong>' . __('version', 'locatepress') . '</strong> : ' . esc_html__($version, 'locatepress') . '</p>';
            echo '<span><p class= "addon-date"><strong>' . __('Release Date', 'locatepress') . '</strong> : ' . esc_html($date) . '</p></span>';
            echo '<p>' . esc_html__($desc, 'locatepress') . '</p></div>';
            echo '<a href="' . esc_url($url) . '" class="addon-button ' . esc_attr($btnColor) . '">' . esc_html($buttonText) . '<a/>';
            echo '</div></div>';
        }
        ?>


    </div>

</div>
