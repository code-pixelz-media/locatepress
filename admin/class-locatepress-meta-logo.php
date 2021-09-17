<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Register_Metabox_Logo{

    public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'locatepress-logo',
			__( 'Logo', 'locatepress' ),
			array( $this, 'locatepress_display_meta' ),
			'map_listing',
			'side',
			'high'
		);

	}

    public function locatepress_display_meta( $post ) {

        		// Add nonce for security and authentication.
		wp_nonce_field( 'locatepress_logo_action', 'locatepress_meta_logo' );

		// Retrieve an existing value from the database.
		$image_id = get_post_meta( $post->ID, 'locatepress_logo', true );

  		// Set default values.
		if( empty( $image_id ) ) $image_id = '';

        echo '<tr class="form-field term-group-wrap">';
        echo '<th scope="row">';
        echo '</th>';
        echo '<td>';
        echo '<input type="hidden" id="locatepress_listing_logo" name="locatepress_logo" value="' . esc_html($image_id) . '">';
        echo '<div id="locatepress_listing_logo-wrapper">';

        if ($image_id) {
            echo wp_get_attachment_image($image_id, 'thumbnail');
            $style = 'display:inline-block';
        }else{
            $style = 'display:none';
        }
        
        echo '</div>';
        echo '<p>';
        echo '<input type="button" style=" margin: 0px 5px" class="button button-secondary listing_type_upload_logo_button" id="add_logo_button" name="add_logo_button" value="' . __('Add Image', 'locatepress') . '">';
        echo '<input type="button" style="'.esc_html($style).'" class="button button-secondary remove_logo_button" id="remove_logo_button" name="remove_logo_button" value="' . __('Remove Image', 'locatepress') . '">';
        echo '</p>';
        echo '</td>';
        echo '</tr>';

       wp_enqueue_script('logo-script');




    }

    public function save_metabox( $post_id, $post ) {

		 // Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['locatepress_meta_logo'] ) ? sanitize_text_field($_POST['locatepress_meta_logo']) : '';
		$nonce_action = 'locatepress_logo_action';

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

        
        $locatepress_logo_id = isset( $_POST[ 'locatepress_logo' ] ) ? sanitize_text_field( $_POST[ 'locatepress_logo' ] ) : '';
        // $x = isset( $_POST[ 'locatepress_logo_text' ] ) ? sanitize_text_field( $_POST[ 'locatepress_logo_text' ] ) : '';

        update_post_meta( $post_id, 'locatepress_logo', $locatepress_logo_id );
       // update_post_meta( $post_id, 'locatepress_logo_text', 'teste' );


    }

}