<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Register_Metabox_Gallery{
    public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'locatepress-gallery',
			__( 'Image Gallery', 'locatepress' ),
			array( $this, 'locatepress_display_meta' ),
			'map_listing',
			'advanced',
			'default'
		);

	}

    public function locatepress_display_meta( $post ) {

        global $post;
        $image_gallery_data = get_post_meta( $post->ID, 'image_gallery_data', true );
        // Use nonce for verification
        wp_nonce_field( 'locatepress_lisitng_gallery_action', 'locatepress_lisitng_gallery' );

        echo '<div id="dynamic_cont">';
            echo '<div id="img_box_container">';
                if ( isset( $image_gallery_data['img_url'] ) )
                {
                for( $i = 0; $i < count( $image_gallery_data['img_url'] ); $i++ )
                {
                echo '<div class="gallery_single_row locatepress-image">';
                    echo '<div class="gallery_area_div image_container ">';?>
                        <img class="gallery_img_img" src="<?php esc_html_e( $image_gallery_data['img_url'][$i] ); ?>" height="55" width="55" onclick="open_media_uploader_image_this(this)"/>
                        <input type="hidden"
                               class="meta_img_url"
                               name="image_gallery_data[img_url][]"
                               value="<?php esc_html_e( $image_gallery_data['img_url'][$i] ); ?>"
                        />
                    </div>
                    <div class="gallery_area_div">
                        <span class="button remove" onclick="remove_img(this)" title="Remove"/><i class="fas fa-trash-alt"></i></span>
                    </div>
                    <div class="clear" />
                </div>
            </div>
            <?php
            }
            }
            ?>
        </div>
        <div style="display:none" id="main_box">
            <div class="gallery_single_row">
                <div class="gallery_area_div image_container" onclick="open_media_uploader_image(this)">
                    <input class="meta_img_url" value="" type="hidden" name="image_gallery_data[img_url][]" />
                </div>
                <div class="gallery_area_div">
                    <span class="button remove" onclick="remove_img(this)" title="Remove"/><i class="fas fa-trash-alt"></i></span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div id="add_gallery_single_row">
            <input class="button add" type="button" value="+" onclick="open_media_uploader_image_plus();" title="Add image"/>
        </div>
       <?php echo  '</div>';
        wp_enqueue_script('gallery-script');

    }
    
    public function save_metabox( $post_id, $post ) {

        // Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['locatepress_lisitng_gallery'] ) ? $_POST['locatepress_lisitng_gallery'] : '';
		$nonce_action = 'locatepress_lisitng_gallery_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;
 

        if ( $_POST['image_gallery_data'] )
        {
            // Build array for saving post meta
            $image_gallery_data = array();
            for ($i = 0; $i < count( $_POST['image_gallery_data']['img_url'] ); $i++ )
            {
                if ( '' != $_POST['image_gallery_data']['img_url'][$i])
                {
                    $image_gallery_data['img_url'][]  = esc_url_raw($_POST['image_gallery_data']['img_url'][ $i ]);
                }
            }

            if ( $image_gallery_data )
                update_post_meta( $post_id, 'image_gallery_data', $image_gallery_data );
        }


    }
}