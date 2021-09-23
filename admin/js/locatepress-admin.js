jQuery(document).ready( function($) {

  function locate_press_upload_marker_icon(button_class){

    var _custom_media = true,
     _orig_send_attachment = wp.media.editor.send.attachment;

    $('body').on('click','.'+button_class, function(e){
       var button_id = '#'+$(this).attr('id'),
           send_attachment_bkp = wp.media.editor.send.attachment,
           button = $(button_id),
           _custom_media = true;

    wp.media.editor.send.attachment = function(props, attachment){
      
      if ( _custom_media ) {
        
        $('#listing_type-icon').val(attachment.id);
        $('#listing_type-icon-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
        $('#listing_type-icon-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
        $('#remove_icon_button').css('display','inline-block');
      } 
      else 
      {
        return _orig_send_attachment.apply( button_id, [props, attachment] );
      }

    }
    wp.media.editor.open(this);
    
  });

}//function ends
locate_press_upload_marker_icon("listing_type_upload_media_button");

$('body').on('click','.remove_icon_button',function(){
  $('#listing_type-icon').val('');
  $('#listing_type-icon-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
  $('#remove_icon_button').css('display','none');

});

// $(document).ajaxComplete(function(event, xhr, settings) {

//   var queryStringArr = settings.data.split('&');
//     if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
//       var xml = xhr.responseXML;
//       $response = $(xml).find('term_id').text();
//     if($response!=""){
    
//       $('#listing_type-icon-wrapper').html('');
//       $('#remove_icon_button').css('display','none');


//     }
//   }
//   });

 function save_main_options_ajax() {
      $('#save-settings').submit( function () {
        var b =  $(this).serialize();
        $('.save-settings-dash').val('Saving...');
        $.post( 'options.php', b )
        .error( function() {
         var html = `<div class="error-box"></div>`;
        $(html).insertAfter('.settings-submit');
         setTimeout(function(){ $('.error-box').remove(); }, 1000);
         $('.save-settings-dash').val('Confirm Changes');
        })
        .success( function() {
        var html = `<div class="success-box">Settings Saved Successfully</div>`;
        $(html).insertAfter('.settings-submit');
         setTimeout(function(){ $('.success-box').remove(); }, 1000);
         $('.save-settings-dash').val('Confirm Changes');
        });
        return false;    
      });
  }

save_main_options_ajax();

});