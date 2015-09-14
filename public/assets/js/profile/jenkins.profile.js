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
var add_photos_button           = $('.add_photos_btn');
var photos_upload_form          = $('form.photos_upload');
var new_album_create            = $('#new-album-create');
var album_progress_bar          = $('#album-upload-progress-bar');
var form_album_upload           = $('form.album_upload');
var loader_image_div            = $('.loader-album-div');
var save_album                  = $('#save-album');
var albums_view                 = $('.albums-view');
var new_album_div               = $('.new-album-div');
var delete_album                = $('.delete-album');

//variables
var handler = {};
var offset = 0, endOfFeed = false, backgroundPosition, photos_array = [], current_url, album_images = [];

//init function
var init = function(){

	//init slim scroll
	slimscroll_avatar_div.slimScroll({
        height: '250px'
    });

    handler.is_photos_view();

	//workers
	new_album_create.click(handler.new_album);
	change_banner_btn.click(handler.click_file);
	file_change_banner.change(handler.change_banner);
	resize_banner.click(handler.banner_resize);
	close_resize_banner.click(handler.banner_resize_close);
	save_resize_banner.click(handler.banner_resize_save);
	//$('ul.profile-buttons').find('li.li-icons').click(handler.profile_page_btn_handler);
	add_photos_button.click(handler.add_photos_fn);
	$(document).on('click', '.photo-select', '', function(){
		photos_upload.click();
	});

	delete_album.click(handler.delete_album);

	save_album.click(handler.add_new_album);

	$(document).on('change', 'input#album_upload', '', handler.new_album_submit);

	$(document).on('change', '#photos_upload', '', handler.add_photos_uploader);

	$(document).on('click', '#upload-photos-btn', handler.submit_selected_photos);

	$(document).on('click', '#cancel-upload', handler.close_modal);

	photos_upload_form.ajaxForm({
		beforeSend: function() { //before sending form
			$('.modal-loader').show();
		},
		uploadProgress: function(event, position, total, percentComplete) { //on progress
			$('.modal-loader').find('span.text').text(' uploading... '+percentComplete+' %');
		},
		complete: function(response) { // on complete
			var data = jQuery.parseJSON(response.responseText);
			var template;
			$('.modal-loader').hide();
			total_utility_modal.modal('hide');
			for(var i = 0; i < data.images.length; i ++){
				template += '<div class="item photo-album-item"><span class="overlay-photos-delete-btn"> × </span> <img width="100" class="feedPhotos" data-id="'+data.feed_id+'" data-img="'+data.images[i]+'" src="/uploads/'+data.images[i]+'"></div>';
			}

			$('.img-collage-div').append(template);
			$('.img-collage-div').gridalicious({gutter: 2});
		}
	});

	form_album_upload.ajaxForm({
		beforeSend: function() { //before sending form
			//close the modal
			album_images = [];
			loader_image_div.removeClass('hidden');
			new_album_div.removeClass('hidden');
			albums_view.addClass('hidden');

		},
		uploadProgress: function(event, position, total, percentComplete) { //on progress
			album_progress_bar.attr('style', 'width:'+percentComplete+'%');
			$('.upload-perc').text(percentComplete+' %');
		},
		complete: function(response) { // on complete
			album_images = jQuery.parseJSON(response.responseText);
			var template = '';
			for(var i = 0; i < album_images.length; i ++) {
				template += '<div class="item photo-album-item"><span class="overlay-photos-delete-btn"> × </span> <img width="100" class="feedPhotos" data-img="'+album_images[i]+'" src="/uploads/'+album_images[i]+'"></div>';
			}

			$('.images-div').html(template);
			$('.images-div').gridalicious({gutter: 2});
			loader_image_div.addClass('hidden');
			save_album.removeClass('disabled');
		}
	});

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
	delete_album: function(){
		var self = $(this);
		var dataString = "feed_id="+self.data("id");
		$.confirm({
		    title: 'Delete Album',
		    content: 'Are you sure you want to delete this album',
		    confirmButton: 'Delete',
		    theme: 'supervan',
		    keyboardEnabled: true,
		    confirmButtonClass: 'btn-primary',
		    animation: 'bottom',
		    confirm: function(){
				$.ajax({
				    type  : 'POST',
				    url   : '/delete_post',
				    data  : dataString,
					beforeSend: function(){
						self.find('div#delete_loader').removeClass("hidden");
						self.find('div#post_dropdown').addClass("hidden");
					},
					success: function(html){
						self.parents('.col-sm-6.col-md-4').hide('slow');
					},
					complete: function(responseText){

					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
					    alert(errorThrown)
					}
				});
		    }
		});
	},
	add_new_album: function(){
		var self        = $(this);
		var album_desc  = $('#album-desc').val();
		var album_title = $('#album-title').val();

		$.ajax({
			type     : 'POST',
			url      : '/save_new_album',
			data     : { album_desc : album_desc, album_title: album_title, album_images: album_images  },
			beforeSend: function(){
				self.addClass('background-transparent-hover').addClass('background-transparent').html('<div class="loader loader-inner ball-pulse"><div></div><div></div><div></div></div>');
			},
			success: function(html){
				location.reload();
			},
			complete: function(data){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			 alert(errorThrown)
			}
	 	});
	},
	new_album: function(){
		$('input#album_upload').click();
	},
	new_album_submit: function(){
		form_album_upload.submit();
	},
	//if url has photos show photos div
	is_photos_view: function(){
		if(window.location.href.split('/')[5] == "photos"){
			$('ul.profile-buttons').find('li[data-type="photos"]').click();
		}
	},
	close_modal: function(){
		total_utility_modal.modal('hide');
	},
	submit_selected_photos: function(){
		photos_upload_form.submit();
	},
	add_photos_uploader: function(e){
		var photo_select_count = 0;
		total_utility_modal.find('.modal-body').html('<h4 id="photo-selected-count"></h4> <div class="row" id="uploaded-photo-row-preview"></div>');
		total_utility_modal.find('.modal-body').addClass('padding-top-sm');
		var fileCollection = new Array();
		var files = e.target.files;
		$.each(files, function(i, file){
			fileCollection.push(file);
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function(e){
				photo_select_count ++;
				var template = ''+
				  '<div class="col-xs-6 col-md-3 plsm prsm">'+
				    '<a href="#" class="thumbnail">'+
				      '<img style="height:70px;" src="'+e.target.result+'" alt="...">'+
				    '</a>'+
				  '</div>';
				total_utility_modal.find('#uploaded-photo-row-preview').append(template);
				total_utility_modal.find('.modal-body').find('#photo-selected-count').text(photo_select_count + ' photos selected');
			};
		});

		total_utility_modal.find('.modal-body').append('<div class="row padding-sm"><input type="text" id="title_photo_upload" class="form-control hidden" placeholder="Say something about these photos.."></input> <br/> <button id="upload-photos-btn" class="btn btn-primary">Upload</button> <button id="cancel-upload" class="btn btn-default">Cancel</button></div>');
	},
	add_photos_fn: function(){
		total_utility_modal.modal('show');
		total_utility_modal.find('.modal-body').html('<div class="add-stuff-btn photo-select">Click to upload photos</div>');
		total_utility_modal.find('h4').html('<span class="icon-cloud-upload text-muted"></span> Upload photos');
	},
	profile_page_btn_handler: function(){
		var self = $(this);
		handler.hide_all_panels();
		$('#'+self.data('type')+'-container').removeClass('hidden');
		$('ul.profile-buttons').find('li.li-icons').removeClass('li-icons-active');
		self.addClass('li-icons-active');

		if(self.data('type') == "photos"){
			$('.img-collage-div').gridalicious({gutter: 2});
		}
	},
	hide_all_panels: function(){
		$('#about-container').addClass('hidden');
		$('#photos-container').addClass('hidden');
		$('#profile-container').addClass('hidden');
		$('#friends-container').addClass('hidden');
	},
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
				profile_img.attr('src', '../uploads/thumb_'+name+'');
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
