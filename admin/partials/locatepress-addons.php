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

            // Will dump a beauty json :3
            $addons = json_decode($result, true);
            //print_r($data);

            foreach($addons as $addon){
                $title = $addon['name'];
                $desc = $addon['about'];
                $image = $addon['icon'];
                $url = $addon['url'];
                $address = $addon['address'];
                echo '<a href="'.$url.'">';
                echo '<div class="singleaddon-wrap col-md-3"><div class="addons-wrap-inner">';
                echo '<img src="'.$image.'"><div class="addons-content">';
                echo '<h4>'.$title.'</h4>';
                echo '<h6>'.$address.'</h6>';
                echo '<p>'.$desc.'</p></div>';
                echo '</div></div>';
                echo '</a>';
            }
        ?>
        
        
    </div>

</div>