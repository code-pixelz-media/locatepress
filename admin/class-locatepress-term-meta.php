<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Locatepress_Term_Meta
{

    private $taxslug;

    public function locatepress_initialize_term_meta()
    {

        add_action('location_type_add_form_fields', array($this, 'locatepress_add_marker_icon'), 10, 2);

        add_action('created_location_type', array($this, 'locatepress_save_image_data'), 10, 2);

        add_action('location_type_edit_form_fields', array($this, 'locatepress_update_location_type_icon'), 10, 2);

        add_action('edited_location_type', array($this, 'locatepress_updated_location_type_icon'), 10, 2);

        add_action('admin_enqueue_scripts', array($this, 'locatepress_load_media'), 10, 2);

    }

    public function locatepress_load_media()
    {

        wp_enqueue_media();
    }

    public function locatepress_add_marker_icon($taxonomy)
    {

        echo '<div class="form-field term-locate_press_marker_icon-wrap">';
        echo '	<input type="hidden" id="location_type-icon" name="location_type-icon" class="custom_media_url" value="">';
        echo ' 	<div id="location_type-icon-wrapper"></div>';
        echo '	<p>';
        echo '  	<input type="button" class="button button-secondary location_type_upload_media_button" id="add_icon_button" 			name="add_icon_button" value="' . __('Add Icon', 'locatepress') . '">';
        echo '		<input type="button" class="button button-secondary remove_icon_button" id="remove_icon_button" name="remove_icon_button" value="' . __('Remove Icon', 'locatepress') . '">';
        echo '	</p>';
        echo '</div>';

    }

    public function locatepress_save_image_data($term_id, $tt_id)
    {

        if (isset($_POST['location_type-icon']) && '' !== $_POST['location_type-icon']) {
            $image = $_POST['location_type-icon'];
            add_term_meta($term_id, 'location_type-icon', $image, true);
        }

    }

    public function locatepress_update_location_type_icon($term, $taxonomy)
    {
        $icon_id = get_term_meta($term->term_id, 'location_type-icon', true);

        echo ' <tr class="form-field term-group-wrap">';
        echo '		<th scope="row">';
        echo '  		<label for="location_type-icon">' . __('Marker Icon', 'locatepress') . '</label>';
        echo '		</th>';
        echo '		<td>';
        echo '			<input type="hidden" id="location_type-icon" name="location_type-icon" value="' . $icon_id . '">';

        echo '    		<div id="location_type-icon-wrapper">';
        if ($icon_id) {
            echo wp_get_attachment_image($icon_id, 'thumbnail');
        }
        echo '    		</div>';
        echo '     		<p>';
        echo '     			<input type="button" class="button button-secondary location_type_upload_media_button" id="add_icon_button"name="add_icon_button" value="' . __('Add Icon', 'locatepress') . '">';
        echo '				<input type="button" class="button button-secondary remove_icon_button" id="remove_icon_button" name="remove_icon_button" value="' . __('Remove Icon', 'locatepress') . '">';
        echo '      		</p>';
        echo '     </td>';
        echo ' </tr>';

    }

    public function locatepress_updated_location_type_icon($term, $taxonomy)
    {

        if (isset($_POST['location_type-icon']) && '' !== $_POST['location_type-icon']) {
            $image = $_POST['location_type-icon'];
            update_term_meta($term, 'location_type-icon', $image);
        } else {
            update_term_meta($term, 'location_type-icon', '');
        }

    }

}
