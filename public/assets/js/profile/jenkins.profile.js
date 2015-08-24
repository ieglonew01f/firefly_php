//cache
var change_banner_btn           = $('.change-banner');
var file_change_banner          = $('#file_banner');
var file_profile_picture        = $('#file_pp');
var form_profile_picture        = $("#form_change_profile_picture");
var banner_form                 = $('form.banner_form');
var banner_container            = $('div.well-banner');
var banner_loader               = $('div.banner-loader');
var upload_profile_pbt          = $('#upload_from_computer');
var profile_img                 = $('img.profile-img');
var total_utility_modal         = $('#total_utility_modal');
var slimscroll_avatar_div       = $('div.slimscroll-avatar');
var click_thumbnail             = $('img.img-cthumbnail');
var feed_container              = $('div#feeds_cont');
var resize_banner               = $('.btn-resize');
var close_resize_banner         = $('.banner-resize-close-btn');
var save_resize_banner          = $('.banner-resize-save-btn');
//variables
var handler = {};
var offset = 0, endOfFeed = false, backgroundPosition;

//init function
var init = function(){

	//init slim scroll
	slimscroll_avatar_div.slimScroll({
        height: '250px'
    });

	//workers
	change_banner_btn.click(handler.click_file);
	file_change_banner.change(handler.change_banner);
	resize_banner.click(handler.banner_resize);
	close_resize_banner.click(handler.banner_resize_close);
	save_resize_banner.click(handler.banner_resize_save);

	//change profile picture upload from computer
	upload_profile_pbt.click(handler.click_file_profile_picture);
	file_profile_picture.change(handler.change_profile_picture);

	//change profile picture as avatar
	click_thumbnail.click(handler.set_avatar);

	//ajax file upload listner
	banner_form.ajaxForm({
		beforeSend: function() { //before sending form
			banner_loader.show();
		},
		uploadProgress: function(event, position, total, percentComplete) { //on progress
			banner_loader.find('span.text').text(' uploading... '+percentComplete+' %');
		},
		complete: function(response) { // on complete
			banner_loader.hide();
			banner_container.attr('style','background:url("../uploads/'+response.responseText+'");background-size:cover;');
			//alert(response.responseText);
		}
	});

	form_profile_picture.ajaxForm({
		beforeSend: function() { //before sending form
			//close the modal
			total_utility_modal.modal('hide');
		},
		uploadProgress: function(event, position, total, percentComplete) { //on progress
			profile_img.text('uploading... '+percentComplete+' %');
		},
		complete: function(response) { // on complete
			profile_img.attr('src', '../uploads/'+response.responseText+'');
		}
	});

	//load more feeds handler
	//load more feeds on scroll bottom
	$(window).scroll(function() {
	 if($(window).scrollTop() + $(window).height() == $(document).height()) {
		 handler.loadMoreFeeds();
	 }
	});
}

//helpers
handler  = {
	banner_resize: function(){
		$('#banner_resize').backgroundDraggable({ bound: false, axis: 'y', 
			  done: function() {
			    backgroundPosition = $('#banner_resize').css('background-position');
			  }
		});
		$('#banner_resize').addClass('cursor-resize').find('.row').hide();
		$('.resize-save-offset').removeClass('hidden');
	},
	banner_resize_save: function(){
		var self = $(this);
		$.ajax({
			 type     : 'POST',
			 url      : '/save_banner_position',
			 data     : { banner_position : backgroundPosition },
			 beforeSend: function(){
			 	self.addClass('background-transparent-hover').html('<div class="loader loader-inner ball-pulse"><div></div><div></div><div></div></div>');
			 },
			 success: function(html){
			 	self.html('Save and Close').removeClass('background-transparent-hover');
			 },
			 complete: function(data){
			 	handler.banner_resize_close();
			 },
			 error: function(XMLHttpRequest, textStatus, errorThrown) {
				 alert(errorThrown)
			 }
	 	});
	},
	banner_resize_close: function(){
		$('#banner_resize').removeClass('cursor-resize').find('.row').show();
		$('.resize-save-offset').addClass('hidden');
	},
	loadMoreFeeds: function(){
		if(!endOfFeed){ //while not end of feed reached
			offset = parseInt(offset) + 10; //load next 10 feeds
			$.ajax({
				 type     : 'POST',
				 url      : '/load_more_feeds',
				 data     : { type:'1', offset : offset },
				 beforeSend: function(){

				 },
				 success: function(html){
					 $(html).hide().appendTo(feed_container).fadeIn(1000);
					 if(!html){ endOfFeed = true; $('<div class="well well-sm bg-white text-center"><b>Nothing to see here<b/></div>').hide().appendTo(feed_container).fadeIn(1000); }
				 },
				 complete: function(data){
					 handler_feeds.init_photo_grid({gutter: 1});
				 },
				 error: function(XMLHttpRequest, textStatus, errorThrown) {
					 alert(errorThrown)
				 }
		 	});
		}
	},
	click_file: function(){
		file_change_banner.click();
	},
	click_file_profile_picture: function(){
		file_profile_picture.click();
	},
	change_banner:function(){
		banner_form.submit();
	},
	change_profile_picture:function(){
		form_profile_picture.submit();
	},
	set_avatar: function(){

		var name       = $(this).data('name')
		var dataString = "avatarname="+name;
		$.ajax({
		    type  : 'POST',
		    url   : '/set_avatar',
		    data  : dataString,
			beforeSend: function(){
				profile_img.attr('src', '../uploads/'+name+'');
				total_utility_modal.modal('hide');
			},
			success: function(){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	}
}



//document ready
$(document).ready(init());
