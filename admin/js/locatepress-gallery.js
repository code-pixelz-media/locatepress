function remove_img(value) {
    var parent=jQuery(value).parent().parent();
    parent.remove();
  }
  var media_uploader = null;
  function open_media_uploader_image(img){
    media_uploader = wp.media({
        frame:    "post",
        state:    "insert",
        multiple: false
    });
    media_uploader.on("insert", function(){
        var json = media_uploader.state().get("selection").first().toJSON();
        var image_url = json.url;
        var html = '<img class="gallery_img_img" src="'+image_url+'" height="55" width="55" onclick="open_media_uploader_image_this(this)"/>';
        console.log(image_url);
        jQuery(img).append(html);
        jQuery(img).find('.meta_img_url').val(image_url);
    });
    media_uploader.open();
  }
  function open_media_uploader_image_this(img){
    media_uploader = wp.media({
        frame:    "post",
        state:    "insert",
        multiple: false
    });
    media_uploader.on("insert", function(){
        var json = media_uploader.state().get("selection").first().toJSON();
        var image_url = json.url;
        console.log(image_url);
        jQuery(img).attr('src',image_url);
        jQuery(img).siblings('.meta_img_url').val(image_url);
    });
    media_uploader.open();
  }
  
  function open_media_uploader_image_plus(){
    media_uploader = wp.media({
        frame:    "post",
        state:    "insert",
        multiple: true
    });
    media_uploader.on("insert", function(){
  
        var length = media_uploader.state().get("selection").length;
        var images = media_uploader.state().get("selection").models
  
        for(var iii = 0; iii < length; iii++){
            var image_url = images[iii].changed.url;
            var box = jQuery('#main_box').html();
            jQuery(box).appendTo('#img_box_container');
            var element = jQuery('#img_box_container .gallery_single_row:last-child').find('.image_container');
            var html = '<img class="gallery_img_img" src="'+image_url+'" height="55" width="55" onclick="open_media_uploader_image_this(this)"/>';
            element.append(html);
            element.find('.meta_img_url').val(image_url);
  
           // console.log(image_url);
        }
    });
    media_uploader.open();
  }
  jQuery(function() {
    jQuery("#img_box_container").sortable();
  });
  