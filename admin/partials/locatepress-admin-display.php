<?php 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$lp_options = get_option( 'locate_press_set' ); 
$filter_page = '';
if(isset($lp_options['lp_filter_page'])){
    $filter_page = $lp_options['lp_filter_page'];
}

?>
<div id="general">
    <h2 class="tab-title"><?php _e('General Settings','locatepress'); ?></h2>
    <div class="form-group lp-options">
        <label for=""><?php _e('Google Map Api Key','locatepress'); ?></label>
        <div class="form-control-wrap">
            <input class="form-control" name="locate_press_set[lp_map_api_key]"  type="text" placeholder="<?php _e('Map Api Key','locatepress'); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='Map Api Key'" value="<?php if(isset($lp_options['lp_map_api_key'])) : echo $lp_options['lp_map_api_key'];  else : echo 'Google Map api key'; endif;  ?>">
        </div>
    </div>
    <div class="form-group os-options">
        <label for=""><?php _e('Map Box Api Key','locatepress'); ?></label>
        <div class="form-control-wrap">
            <input class="form-control" name="locate_press_set[os_map_api_key]"  type="text" placeholder="<?php _e('Map Api Key','locatepress'); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='Map Api Key'" value="<?php if(isset($lp_options['os_map_api_key'])) :echo $lp_options['os_map_api_key']; else : echo 'Map box api key'; endif;  ?>">
        </div>
    </div>
    <div class="form-group">
        <label for=""><?php _e('Select Page To Display Results','locatepress'); ?></label>
        <div class="form-control-wrap">
             <?php wp_dropdown_pages($args=array(
                'class'=>'lp-custom-select ',
                'name' =>'locate_press_set[lp_filter_page]',
                'value'=>$filter_page,
                'selected'=> $filter_page,
                'show_option_none'  => __('Select Page','locatepress'), 
             )); ?>
        </div>
    </div>
</div>
<div id="search-bar">
    <h2 class="tab-title"><?php _e('Search Bar Settings','locatepress'); ?></h2>
    <div class="form-group">
        <label for="locate_press_set[lp_search_disp]"><?php _e('Search Bar position','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('top');?>" name="locate_press_set[lp_search_disp]" <?php if(isset( $lp_options['lp_search_disp'])) {echo  checked( $lp_options['lp_search_disp'], 'top', false );}else{ echo 'checked';} ?> >
                <span class="custom-radio"><?php _e('Top','locatepress'); ?></span>
            </div>
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('left');  ?>" name="locate_press_set[lp_search_disp]" <?php if(isset( $lp_options['lp_search_disp'])) echo  checked( $lp_options['lp_search_disp'], 'left', false ); ?>>
                <span class="custom-radio"><?php _e('Left','locatepress'); ?></span>
            </div>
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('right');?>" name="locate_press_set[lp_search_disp]" <?php if(isset( $lp_options['lp_search_disp']))  echo  checked( $lp_options['lp_search_disp'], 'right', false ); ?> >
                <span class="custom-radio"><?php _e('Right','locatepress'); ?></span>
            </div>

            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('buttom');?>" name="locate_press_set[lp_search_disp]" <?php if(isset( $lp_options['lp_search_disp'])) echo  checked( $lp_options['lp_search_disp'], 'buttom', false ); ?> >
                <span class="custom-radio"><?php _e('Bottom','locatepress'); ?></span>
            </div>

        </div>
    </div>
    <div class="form-group">
        <label for=""><?php _e('Disable Keyword Search','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_ky_search]" value="<?php echo esc_attr('disabled'); ?>" <?php if(isset($lp_options['lp_ky_search'])) echo  checked( $lp_options['lp_ky_search'], 'disabled', false ); ?> >
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for=""><?php _e('Disable Location Search','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" value="<?php echo esc_attr('disabled'); ?>" name="locate_press_set[lp_location_search]" <?php if(isset($lp_options['lp_location_search'])) echo  checked( $lp_options['lp_location_search'], 'disabled', false ); ?>>
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for=""><?php _e('Disable Location Type Search','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" value="<?php echo esc_attr('disabled'); ?>" name="locate_press_set[lp_locationtype_search]" <?php if(isset($lp_options['lp_locationtype_search'])) echo  checked( $lp_options['lp_locationtype_search'], 'disabled', false ); ?> >
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for=""><?php _e('Disable Community Search','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_com_search]" value="<?php echo esc_attr('disabled'); ?>" <?php if(isset($lp_options['lp_com_search'])) echo  checked( $lp_options['lp_com_search'], 'disabled', false ); ?>>
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for=""><?php _e('Disable Reset Button','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_filter_reset-btn]" value="<?php echo esc_attr('disabled'); ?>" <?php if(isset($lp_options['lp_filter_reset-btn'])) echo  checked( $lp_options['lp_filter_reset-btn'], 'disabled', false ); ?>>
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>

</div>
<div id="map-settings">
    <h2 class="tab-title"><?php _e('Map Settings','locatepress'); ?></h2>
    <div class="form-group">
    <?php do_action('locatepress_before_map_type');?>
        <label for="locate_press_set[lp_search_disp]"><?php _e('Map Type','locatepress'); ?></label>
        <div class="form-control-wrap lp-options">
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('roadmap');?>" name="locate_press_set[lp_map_type]" <?php 
                if(isset($lp_options['lp_map_type'])){ 
                    echo  checked( $lp_options['lp_map_type'], 'roadmap', false );
                }else{ 
                    echo 'checked'; 
                } 
                ?>>
                <span class="custom-radio"><?php _e('Road Map','locatepress'); ?></span>
            </div>
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('hybrid');  ?>" name="locate_press_set[lp_map_type]" <?php if(isset($lp_options['lp_map_type'])) echo  checked( $lp_options['lp_map_type'], 'hybrid', false ); ?>>
                <span class="custom-radio"><?php _e('Hybrid','locatepress'); ?></span>
            </div>
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('satellite');?>" name="locate_press_set[lp_map_type]" <?php if(isset($lp_options['lp_map_type']))echo  checked( $lp_options['lp_map_type'], 'satellite', false ); ?> >
                <span class="custom-radio"><?php _e('Satellite ','locatepress'); ?></span>
            </div>

            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('terrain');?>" name="locate_press_set[lp_map_type]" <?php if(isset($lp_options['lp_map_type']))echo  checked( $lp_options['lp_map_type'], 'terrain', false ); ?> >
                <span class="custom-radio"><?php _e('Terrain','locatepress'); ?></span>
            </div>

        </div>
        <div class="form-control-wrap os-options">
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('roadmap');?>" name="locate_press_set[os_map_type]" <?php 
                if(isset($lp_options['os_map_type'])){ 
                    echo  checked( $lp_options['os_map_type'], 'roadmap', false );
                }else{ 
                    echo 'checked'; 
                } 
                ?>>
                <span class="custom-radio"><?php _e('Streets','locatepress'); ?></span>
            </div>
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('hybrid');  ?>" name="locate_press_set[os_map_type]" <?php if(isset($lp_options['os_map_type'])) echo  checked( $lp_options['os_map_type'], 'hybrid', false ); ?>>
                <span class="custom-radio"><?php _e('Satellite-Streets','locatepress'); ?></span>
            </div>
            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('satellite');?>" name="locate_press_set[os_map_type]" <?php if(isset($lp_options['os_map_type']))echo  checked( $lp_options['os_map_type'], 'satellite', false ); ?> >
                <span class="custom-radio"><?php _e('Satellite','locatepress'); ?></span>
            </div>

            <div class="radio-wrap custom">
                <input type="radio" value="<?php echo esc_attr('terrain');?>" name="locate_press_set[os_map_type]" <?php if(isset($lp_options['os_map_type']))echo  checked( $lp_options['os_map_type'], 'terrain', false ); ?> >
                <span class="custom-radio"><?php _e('Outdoors','locatepress'); ?></span>
            </div>

        </div>
    </div>

    <div class="form-group">
        <label for=""><?php _e('Default Marker','locatepress'); ?></label>
        <input type="hidden" id="upload_image" size="36" name="locate_press_set[lp_default_marker]" value="<?php echo $lp_options['lp_default_marker'] ?>">
        <img src="<?php echo $lp_options['lp_default_marker'] ?>" id="image_display" width="30" height="30" style="display: none;">
        <input id="upload_image_button" class="button" type="button" value="Add">
        <input id="remove_image_button" class="button" type="button" value="Remove">
    </div>

    <div class="form-group">
        <label for=""><?php _e('Disable Full Screen Control','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_full_screen_control]" value="<?php echo esc_attr('off'); ?>" <?php if(isset($lp_options['lp_full_screen_control'])) echo  checked( $lp_options['lp_full_screen_control'], 'off', false ); ?> >
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for=""><?php _e('Disable Zoom Control','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_zoom_control]" value="<?php echo esc_attr('off'); ?>" <?php if(isset($lp_options['lp_zoom_control']))  echo  checked( $lp_options['lp_zoom_control'], 'off', false ); ?> >
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
    <div class="form-group lp-options">
        <label for=""><?php _e('Disable Map Type Control','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_maptype_control]" value="<?php echo esc_attr('off'); ?>" <?php if(isset($lp_options['lp_maptype_control']))  echo  checked( $lp_options['lp_maptype_control'], 'off', false ); ?> >
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
    <div class="form-group lp-options">
        <label for=""><?php _e('Disable Street View','locatepress'); ?></label>
        <div class="form-control-wrap">
            <div class="checkbox-wrap custom">
                <input type="checkbox" name="locate_press_set[lp_streetview_control]" value="<?php echo esc_attr('off'); ?>" <?php if(isset($lp_options['lp_streetview_control'])) echo  checked( $lp_options['lp_streetview_control'], 'off', false ); ?> >
                <span class="custom-checkbox"></span>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function($){

        var imgSrc= $('#upload_image').val();
        if(imgSrc !== ""){
            $('#upload_image_button').hide();
            $('#image_display').css('display','');

        }else{
            $('#remove_image_button').hide();
        }
        var custom_uploader;
        $(document).on('click','#upload_image_button', function(e){
            e.preventDefault();
            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });
            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#upload_image').val(attachment.url);
                $('#image_display').attr('src',attachment.url).css('display','');
                $('#upload_image_button').hide();
                $('#remove_image_button').show();
                

            });
            //Open the uploader dialog
            custom_uploader.open();
        });

        $('#remove_image_button').click(function(e){
          $('#upload_image').val('');
          $('#image_display').css('display','none');
          $(this).hide();
          $('#upload_image_button').show();

        });
    });

    function set_span_val(val){
            document.getElementById('range-ip-val').innerHTML=val;
    }
    //console.log('exists');

    //js to change options as seleceted in dropdpwn added by openstreet addon
    jQuery(document).ready(function($){
        if (document.querySelector('.os-maptype-options') !== null) {
            var mapType = $('.os-custom-select').val();
           // console.log(mapType);
           if(mapType !== '' && mapType == 'google-map'){
            $(".lp-options").show();
            $(".os-options").hide();
           } else if(mapType !=='' && mapType == 'open-street-map'){
            $(".lp-options").hide();
            $(".os-options").show();
           }

           $('.os-custom-select').change(function(){
                if ( this.value == 'google-map')
                    {
                        $(".lp-options").show();
                        $(".os-options").hide();

                    }
                    else if(this.value == 'open-street-map')
                    {
                        $(".lp-options").hide();
                        $(".os-options").show();
                    }
            
            });


        }else{
            $(".lp-options").show();
            $(".os-options").hide();
        }
    });

</script>
<div class="form-group settings-submit">
    <?php submit_button('Confirm Changes','cpm-btn submit save-settings-dash'); ?>
</div>
