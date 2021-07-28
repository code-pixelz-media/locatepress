<div class="addon-wrap">
    <div class="row">
        <div class="addon-logo-wrap col-md-6">    
            <div class="title">
                <h3 class="addon-wrap-title"><?php _e( 'Locatepress Addons', 'locatepress' ); ?></h3>
                <!-- <p><a href="http://wplocatepress.com/"><?php _e( 'Locatepress', 'locatepress' ); ?></a></p> -->
            </div>
        </div>
    </div>
    <div class="row">
        <?php  
            $url = 'http://codepixelz.tech/locatepress/locatepress-addons.json';

			$response = wp_remote_get($url);

			if( is_wp_error( $response ) ) {
				return false; // Bail early
			}

			$body = wp_remote_retrieve_body( $response );
			$addons = json_decode( $body, true );

            foreach($addons as $addon){
                $title      = $addon['name'];
                $desc       = $addon['about'];
                $image      = $addon['icon'];
                $version    = $addon['version'];
                $type       = $addon['type'];
                $slug       = $addon['slug'];
                $date       = $addon['date']; //date created
                $price      = $addon['price'];
                $pluginPath = $slug.'/'.$slug.'.php';
                $pathpluginurl = WP_PLUGIN_DIR .'/'. $pluginPath;
                //check if plugin is downloaded or not
                if (file_exists( $pathpluginurl )){
                    if (is_plugin_active($pluginPath)){
                        $buttonText = "Activated";
                        $url = "#";
                        $btnColor   = 'btn-grey';

                    }else{
                        $btnColor   = 'btn-green';
                        $buttonText = "Activate";
                        $url = admin_url('plugins.php');

                    }
                    
                }else{
                    if ($type == "premium"){
                        $btnColor   = 'btn-red';
                        $buttonText = 'Buy Now : '.$price;
                        $url        = $addon['url'];
                    }else{

                        $btnColor   = 'btn-red';
                        $buttonText = "Download";
                        $url        = $addon['url'];
                    }
                   
                }

                if ($type == 'free'){
                    $ribbontext = 'Free';
                }else{
                    $ribbontext = 'Premium ('.$price.')' ;
                }

                echo '<div class="singleaddon-wrap col-md-3">';
                echo '<div class="ribbon ribbon-top-right"><span>'.$ribbontext.'</span></div>';
                echo '<div class="addons-wrap-inner">';
                echo '<img src="'.$image.'"><div class="addons-content">';
                echo '<h4 class= "addon-title">'.$title.'</h4>';
                echo '<a href="'.$url.'" class="addon-detail-button">Addon Details<a/>';
                echo '<p class= "addon-version"><strong>Version</strong> : '.$version.'</p>';
                echo '<span><p class= "addon-date"><strong>Release Date</strong> : '.$date.'</p></span>';
                //echo '<h6>'.$slug.'</h6>';
                echo '<p>'.$desc.'</p></div>';
                echo '<a href="'.$url.'" class="addon-button '.$btnColor.'">'.$buttonText.'<a/>';
                echo '</div></div>';
            }
        ?>
        
        
    </div>

</div>
