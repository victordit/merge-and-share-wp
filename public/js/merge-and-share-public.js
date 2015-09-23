jQuery(document).ready(function($) {
	
	function getImagesFromGallery(target_button){
		var images= new Array();
		var appid = $(target_button).attr('data-fb-app');
		var container = $(target_button).closest(".merge_share_button").parent();
		container.find('.vd_face_builder').each(function(){
    $(this).find('.slotholder').find('.tp-bgimg').each(function(){
       if($(this).css('opacity')!== null || $(this).css('opacity')!== "undefined" ){
        if($(this).css('opacity') == '1'){
          var img = $(this).attr('data-src');
          //console.log('img',img);
          images.push(img);
        }
       }  
      });
    });

		var orderimages = {};
		for (i = 0; i < images.length; i++) { 
    		if(i === 0){
    			orderimages.left = images[i];
    		}
    		else{
    			orderimages.right = images[i];
    		}
		}
		
		orderimages.fb_appid=appid;
    	return JSON.stringify(orderimages);
    }

  $('.merge_share_button a').on("click",function(e){
    e.preventDefault();
    var images =getImagesFromGallery(this);
    var href  = $(this).attr('href');

    $.ajax({
      type: "POST",
      cache: false,
      url: href,
      data:images,
      success: function (data) {
         
        // on success, post (preview) returned data in fancybox
        var data = JSON.parse(data);
        var mod = data.type;
        var content = data.content;
        $.fancybox(content, {type:mod});       
        }// success
    }); // ajax
  }); // on

});
