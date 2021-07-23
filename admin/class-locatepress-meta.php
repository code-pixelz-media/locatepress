<?php 

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Locatepress_Register_Metabox {



	public function locatepress_init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'locatepress_add_metabox' )         );
		add_action( 'save_post',             array( $this, 'locatepress_save_metabox' ), 10, 2 );

	}

	public function locatepress_add_metabox() {

		add_meta_box(
			'locatepress-location',
			__( 'Location', 'locatepress' ),
			array( $this, 'locatepress_display_meta' ),
			'map_listing',
			'advanced',
			'default'
		);

	}

	public function locatepress_display_meta( $post ) {

		//Get google map api key from settings

		$locate_press_options =  get_option('locate_press_set');

		$locate_press_api_key =  $locate_press_options['lp_map_api_key'];

		// Add nonce for security and authentication.
		wp_nonce_field( 'nonce_action', 'nonce' );

		// Retrieve an existing value from the database.
		$lp_lt  = get_post_meta( $post->ID, 'lp_location_lat_long', true );
		$lp_lc  = get_post_meta( $post->ID, 'lp_location_country', true );
	
		echo '<table class="form-table">';

		echo '	<tr class="lp-geo-data">';

		echo '			<input type="hidden" id="lp_location_lat_long" name="lp_location_lat_long" class="lp_location_lat_long_field widefat" placeholder="' . esc_attr__( 'Latitude/Longitude', 'locatepress' ) . '" value="'.esc_attr($lp_lt).'" autocomplete="off">';
		
		echo '		<td>';
		echo '			<label for="lp_location_country">'.__('Address :','locatepress').'</label>';
		echo '			<input type="text" name="lp_location_country" id="country" class="lp_location_Country_field widefat" placeholder="'.esc_attr__('Country','locatepress').'" value="'.esc_attr($lp_lc).'">';
		echo '		</td>';
		echo '	<tr/>';
		echo '</table>';
		echo '    <input id="lp-search-input" class="controls google-map-admin-search-input" type="text" placeholder="Search Location" style="width:50%" onFocus="geolocate()" >';
		echo '	  <div id="lp-meta-map-canvas" data-latlong="'.$lp_lt.'" style="height: 500px; ">'."\n";
		echo '	  </div>';
		
		wp_enqueue_script('map-script');
		do_action ('locatepress_after_map_canvas');

	}

	public function locatepress_save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$nonce_action = 'nonce_action';

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

		// lat long
		$new_lp_location_lat_long = isset( $_POST[ 'lp_location_lat_long' ] ) ? sanitize_text_field( $_POST[ 'lp_location_lat_long' ] ) : '';
		
		//country
		$new_lp_location_country = isset( $_POST[ 'lp_location_country' ] ) ? sanitize_text_field( $_POST[ 'lp_location_country' ] ) : '';


		update_post_meta( $post_id, 'lp_location_lat_long', $new_lp_location_lat_long );

		update_post_meta( $post_id, 'lp_location_country', $new_lp_location_country );

	}
}

