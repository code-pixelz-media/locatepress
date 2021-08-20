<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Register_Metabox_Listing_Details {


	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'locatepress-listing-details',
			__( 'Listing Details', 'locatepress' ),
			array( $this, 'locatepress_display_meta' ),
			'map_listing',
			'advanced',
			'default'
		);

	}

	public function locatepress_display_meta( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'locatepress_nonce_action', 'locatepress_nonce' );

		// Retrieve an existing value from the database.
		$locatepress_contact_no = get_post_meta( $post->ID, 'locatepress_contact_no', true );
		$locatepress_business_hour = get_post_meta( $post->ID, 'locatepress_business_hour', true );
		$locatepress_yt_url = get_post_meta( $post->ID, 'locatepress_yt-url', true );
		$locatepress_ig_url = get_post_meta( $post->ID, 'locatepress_ig-url', true );
		$locatepress_pin_url = get_post_meta( $post->ID, 'locatepress_pin-url', true );
		$locatepress_fb_url = get_post_meta( $post->ID, 'locatepress_fb-url', true );
		$locatepress_twir_url = get_post_meta( $post->ID, 'locatepress_twir-url', true );

		// Set default values.
		if( empty( $locatepress_contact_no ) ) $locatepress_contact_no = '';
		if( empty( $locatepress_business_hour ) ) $locatepress_business_hour = '';
		if( empty( $locatepress_yt_url ) ) $locatepress_yt_url = '';
		if( empty( $locatepress_ig_url ) ) $locatepress_ig_url = '';
		if( empty( $locatepress_pin_url ) ) $locatepress_pin_url = '';
		if( empty( $locatepress_fb_url ) ) $locatepress_fb_url = '';
		if( empty( $locatepress_twir_url ) ) $locatepress_twir_url = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="locatepress_contact_no" class="locatepress_contact_no_label">' . __( 'Contact No', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="locatepress_contact_no" name="locatepress_contact_no" class="locatepress_contact_no_field" placeholder="' . esc_attr__( 'Contact No', 'locatepress' ) . '" value="' . esc_attr( $locatepress_contact_no ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="locatepress_business_hour" class="locatepress_business_hour_label">' . __( 'Business Hour', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		wp_editor( $locatepress_business_hour, 'locatepress_business_hour', 
		array( 
			'media_buttons' => false,
     		'textarea_rows' => 8,
      		'wpautop' => true,
			'quicktags' => false,
      		'tinymce' => array(
				'toolbar1' => 'formatselect,bold, italic,underline,strikethrough,bullist,numlist,alignleft,aligncenter,alignright,blockquote,link,undo,redo',
				'toolbar2' => '',
			  )
        	
			)
		 );
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="locatepress_social_profiles-url" class="locatepress_social_profiles-url">' . __( 'Social Profiles', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="locatepress_social_profiles" name="locatepress_social-profiles" class="locatepress_social_profiles_field" placeholder="' . esc_attr__( 'https://www.youtube.com/', 'locatepress' ) . '" value="' . esc_attr( $locatepress_yt_url ) . '">';
		echo '		</td>';
		echo '	</tr>';

		// echo'<!-- Add font awesome icons -->
		// <a href="#" class="fa fa-facebook"></a>
		// <a href="#" class="fa fa-twitter"></a>
		// <a href="#" class="fa fa-google"></a>
		// <a href="#" class="fa fa-linkedin"></a>
		// <a href="#" class="fa fa-youtube"></a>
		// <a href="#" class="fa fa-instagram"></a>
		// <a href="#" class="fa fa-pinterest"></a>
		// <a href="#" class="fa fa-snapchat-ghost"></a>
		// <a href="#" class="fa fa-skype"></a>
		// <a href="#" class="fa fa-android"></a>
		// <a href="#" class="fa fa-dribbble"></a>
		// <a href="#" class="fa fa-vimeo"></a>
		// <a href="#" class="fa fa-tumblr"></a>
		// <a href="#" class="fa fa-vine"></a>
		// <a href="#" class="fa fa-foursquare"></a>
		// <a href="#" class="fa fa-stumbleupon"></a>
		// <a href="#" class="fa fa-flickr"></a>
		// <a href="#" class="fa fa-yahoo"></a>
		// <a href="#" class="fa fa-reddit"></a>
		// <a href="#" class="fa fa-rss"></a>';
        

		echo '	<tr>';
		echo '		<th><label for="locatepress_yt-url" class="locatepress_yt-url_label">' . __( 'Youtube link', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="locatepress_yt_url" name="locatepress_yt-url" class="locatepress_yt_url_field" placeholder="' . esc_attr__( 'https://www.youtube.com/', 'locatepress' ) . '" value="' . esc_attr( $locatepress_yt_url ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="locatepress_ig-url" class="locatepress_ig-url_label">' . __( 'Instagram link', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="locatepress_ig_url" name="locatepress_ig-url" class="locatepress_ig_url_field" placeholder="' . esc_attr__( 'https://www.instagram.com/', 'locatepress' ) . '" value="' . esc_attr( $locatepress_ig_url ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="locatepress_pin-url" class="locatepress_pin-url_label">' . __( 'Pinterest link', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="locatepress_pin_url" name="locatepress_pin-url" class="locatepress_pin_url_field" placeholder="' . esc_attr__( 'https://www.pinterest.com/', 'locatepress' ) . '" value="' . esc_attr( $locatepress_pin_url ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="locatepress_fb-url" class="locatepress_fb-url_label">' . __( 'Facebook link', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="locatepress_fb_url" name="locatepress_fb-url" class="locatepress_fb_url_field" placeholder="' . esc_attr__( 'https://www.facebook.com/', 'locatepress' ) . '" value="' . esc_attr( $locatepress_fb_url ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="locatepress_twir-url" class="locatepress_twir-url_label">' . __( 'Twitter link', 'locatepress' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="url" id="locatepress_twir_url" name="locatepress_twir-url" class="locatepress_twir_url_field" placeholder="' . esc_attr__( 'https://twitter.com', 'locatepress' ) . '" value="' . esc_attr( $locatepress_twir_url ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['locatepress_nonce'] ) ? $_POST['locatepress_nonce'] : '';
		$nonce_action = 'locatepress_nonce_action';

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

		// Sanitize user input.
		$locatepress_new_contact_no = isset( $_POST[ 'locatepress_contact_no' ] ) ? sanitize_text_field( $_POST[ 'locatepress_contact_no' ] ) : '';
		$locatepress_new_business_hour = isset( $_POST[ 'locatepress_business_hour' ] ) ? wp_kses_post( $_POST[ 'locatepress_business_hour' ] ) : '';
		$locatepress_new_yt_url = isset( $_POST[ 'locatepress_yt-url' ] ) ? esc_url( $_POST[ 'locatepress_yt-url' ] ) : '';
		$locatepress_new_ig_url = isset( $_POST[ 'locatepress_ig-url' ] ) ? esc_url( $_POST[ 'locatepress_ig-url' ] ) : '';
		$locatepress_new_pin_url = isset( $_POST[ 'locatepress_pin-url' ] ) ? esc_url( $_POST[ 'locatepress_pin-url' ] ) : '';
		$locatepress_new_fb_url = isset( $_POST[ 'locatepress_fb-url' ] ) ? esc_url( $_POST[ 'locatepress_fb-url' ] ) : '';
		$locatepress_new_twir_url = isset( $_POST[ 'locatepress_twir-url' ] ) ? esc_url( $_POST[ 'locatepress_twir-url' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'locatepress_contact_no', $locatepress_new_contact_no );
		update_post_meta( $post_id, 'locatepress_business_hour', $locatepress_new_business_hour );
		update_post_meta( $post_id, 'locatepress_yt-url', $locatepress_new_yt_url );
		update_post_meta( $post_id, 'locatepress_ig-url', $locatepress_new_ig_url );
		update_post_meta( $post_id, 'locatepress_pin-url', $locatepress_new_pin_url );
		update_post_meta( $post_id, 'locatepress_fb-url', $locatepress_new_fb_url );
		update_post_meta( $post_id, 'locatepress_twir-url', $locatepress_new_twir_url );

	}

}

