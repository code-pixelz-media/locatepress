jQuery(document).ready( function($) {

    function locatepress_listing_logo(button_class){
  
      var _custom_media = true,
       _orig_send_attachment = wp.media.editor.send.attachment;
  
      $('body').on('click','.'+button_class, function(e){
         var button_id = '#'+$(this).attr('id'),
             send_attachment_bkp = wp.media.editor.send.attachment,
             button = $(button_id),
             _custom_media = true;
  
      wp.media.editor.send.attachment = function(props, attachment){
        
        if ( _custom_media ) {
          
          $('#locatepress_listing_logo').val(attachment.id);
          $('#locatepress_listing_logo-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
          $('#locatepress_listing_logo-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
          $('#remove_logo_button').css('display','inline-block');
        } 
        else 
        {
          return _orig_send_attachment.apply( button_id, [props, attachment] );
        }
  
      }
      wp.media.editor.open(this);
      
    });
  
  }//function ends
  locatepress_listing_logo("listing_type_upload_logo_button");
  
  $('body').on('click','.remove_logo_button',function(){
    $('#locatepress_listing_logo').val('');
    $('#locatepress_listing_logo-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
    $('#remove_logo_button').css('display','none');
  
  });

  
  });