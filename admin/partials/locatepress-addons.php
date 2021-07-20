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
            $ch = curl_init();
            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the url
            curl_setopt($ch, CURLOPT_URL,$url);
            // Execute
            $result=curl_exec($ch);
            // Closing
            curl_close($ch);
            $addons = json_decode($result, true);
            //print_r($data);

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
                    }else{
                        $buttonText = "Activate";
                        $url = admin_url('plugins.php');

                    }
                    
                }else{
                    if ($type == "premium"){
                        $buttonText = "Buy";
                        $url        = $addon['url'];
                    }else{
                        $buttonText = "Download";
                        $url        = $addon['url'];
                    }
                   
                }

                echo '<div class="singleaddon-wrap col-md-3"><div class="addons-wrap-inner">';
                echo '<img src="'.$image.'"><div class="addons-content">';
                echo '<h4 class= "addon-title">'.$title.'</h4>';
                echo '<p class= "addon-version"><strong>Version</strong> : '.$version.'</p>';
                echo '<span><p class= "addon-date"><strong>Release Date</strong> : '.$date.'</p></span>';
                //echo '<h6>'.$slug.'</h6>';
                echo '<p>'.$desc.'</p></div>';
                echo '<a href="'.$url.'" class="addon-button">'.$buttonText.'<a/>';
                echo '</div></div>';
            }
        ?>
        
        
    </div>

</div>
