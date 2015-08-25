//cache
var status_button         = $("#status_button");
var text_status           = $("#status");
var edit_text             = $("#edit_text");
var loader                = $("#loader");
var feed_content          = $("#feeds_cont");
var video_frame           = $("#video_frame");
var edit_post             = $("li.editpost");
var delete_post           = $("li.deletepost");
var post_card             = $("div.post-container");
var edit_panel            = $("#edit_panel");
var save_editing          = $("#done_editing");
var sc_widget             = $("#scWidget");
var close_edit            = $("#close_edit");
var utility_modal         = $("#utility_modal");
var confirm               = $("#confirm");
var comments_btn          = $("#comments_btn");
var skip_question         = $("#skip_question");
var next_question         = $("#next_question");
var question_div          = $(".question-container");
var question              = $("div.question-bank p");
var input_content         = $("div.input-container");
var answer                = $("#answer");
var text_perc_com         = $("h4.complete-prec-txt");
var progress_bar          = $("div.progress-bar");
var current_c_perc        = $('div.progress-bar').data('value');
var profile_c_div         = $('div.profile-completion-panel');
var photo_select          = $('li.photo-select');
var photo_upload          = $('#photo_upload');
var photo_upload_form     = $('#photo_update_form');
var status_img_div        = $('.status-img-container').find('.row');
var feedPhotos            = $('.feedPhotos');
var viewerModal           = $('#viewerModal');
var viewerDialogMediaBody = $('#viewerDialogMediaBody');
var img_gallery_cnt       = $('.img-container');
var gallery_comments      = $("#gallery_comments");
var input_comment_gallery = $('input#comment_gallery');
var gallery_loader        = $("#gallery_comment_loader");
var gallery_comment_count = $("#comments_count");
var overlay_loader        = $(".overlay-loader");
var sidebar_chat_list     = $(".sidebar-chat-list");
var jenkins_session_data  = $('#jenkins_session_data');
var feed_container        = $('div#feeds_cont');

//variables
var online_users, friend_list = FRIEND_LIST_ARRAY, data, matches, soundcloud, viemo, code, regExp, match, previous_comment_offset = 6, current_c_perc, handler_feeds = {}, photo_array = [], image_counter = 0, img_feed_id = 0;
var youtube_vtitle, youtube_vdesc, youtube_vthumb, youtube_vcode, global_SoundCloud_Link, comment_card_obj, post_card_obj,exec = 0, multiples, remainder, isPhotoUpdate = false, offset = 0, endOfFeed = false;

var session_username = jenkins_session_data.data('username');
var session_fullname = jenkins_session_data.data('fullname');
var session_id       = jenkins_session_data.data('id');
var session_pp       = jenkins_session_data.data('pp');

/* SET API KEY FOR YOUTUBE */
var apiKey = "AIzaSyDLAPqnX5JQ6bqcxZJaxrlaFRLqCQMBZQM";
/* SET API KEY FOR SOUND CLOUD*/
var scapiKey = "243727134d2c71ba214ef1ec60a371d3";

//init function
var init = function(){
	//workers

	//chat workers
	//scroll to bottom
	handler_feeds.scroll_bottom();
	//register on node.js network
	comet.register({'id':session_id, 'username': session_username, 'fullname': session_fullname});

	//recieve message handler
	comet.new_message(function(data){
		//building html
		var chat_message_box = ''+
			'<div class="row margin-bottom-sm">'+
				'<div class="chat-incomming">'+
		           '<div class="chat-message-box">'+
		                '<div class="row">'+
		                    '<div class="col-md-1">'+
		                        '<img class="media-object img-circle" data-src="holder.js/64x64" alt="64x64" src="/uploads/'+data.by_pp+'" data-holder-rendered="true" style="width: 34px; height: 34px;">'+
		                    '</div>'+
		                    '<div class="col-md-10 wa">'+
		                        '<div class="chat-in fs15">'+
		                            data.message+
		                        '</div>'+
		                    '</div>'+
		                '</div>'+
		            '</div>'+
		      	'</div>'+
			'</div>';

		//if chatbox is not open already
		if($('.chat-box-outer[data-username='+data.by_username+']').length <= 0) {
			//check for any other open chat boxes
			//and count them
			var chat_boxes_count = $('.chat-box-outer').length;
			var left_position    = parseInt($('.chat-box-outer').width)*parseInt(chat_boxes_count);

			var chat_body = ''+
				'<div style="left:'+left_position+'px !important;" data-username="'+data.by_username+'" data-fullname="'+data.by_username+'" data-id="'+data.by_id+'" class="chat-box-outer">'+
				    '<div class="well chat-box-head nmb">'+
						'<button id="close-chat-box" type="button" class="close" data-dismiss="alert" aria-label="Close">'+
						  '<span aria-hidden="true" class="text-black">&times;</span>'+
						'</button>'+
				        '<div class="media nmt">'+
				          '<div class="media-left hidden">'+
				            '<a href="/profile/'+data.by_username+'">'+
				              '<img id="inbox-whois" class="media-object img-circle" style="width:28px;height:28px;" src="/uploads/'+data.by_pp+'" alt="...">'+
				            '</a>'+
				          '</div>'+
				          '<div class="media-body">'+
				            '<h5 id="inbox-whois-fullname" class="media-heading fwb nmb">'+data.by_fullname+'</h5>'+
				          '</div>'+
				        '</div>'+
				    '</div>'+
				    '<div class="well chat-box-inner nmb">'+
				    '</div>'+
				    '<div class="well chat-box-well-input">'+
				        '<input class="chat-box-input" placeholder="Type a message..."/>'+
				    '</div>'+
				'</div>';

			$('body').append(chat_body);
			handler_feeds.saveActiveConversation(data.by_username, 1);
			var chatBody = $('.chat-box-outer[data-username="'+data.by_username+'"]').find('.chat-box-inner');
			handler_feeds.show_conv($('.chat-box-outer[data-id="'+data.by_id+'"]'));
			chatBody.append(chat_message_box);
			chatBody.animate({ scrollTop: chatBody[0].scrollHeight}, 400);
		}
		else if($('.chat-box-outer[data-username='+data.by_username+']').length > 0) {
			var chatBody = $('.chat-box-outer[data-username="'+data.by_username+'"]').find('.chat-box-inner');
			chatBody.append(chat_message_box);
			chatBody.animate({ scrollTop: chatBody[0].scrollHeight}, 400);
		}

		//play sound
		ion.sound.play("ping");
	});

	//refresh chat list if new user
	//send friendlist to socket server and check who is online and get list of friends online
	comet.chat_list(friend_list, function(friends_online){
		//make ajax call to get friends_online details
		if(friends_online.length > 0){
			handler_feeds.populateOnlineChatList(friends_online);
		}
		else{
			//nobody is online 
			sidebar_chat_list.empty();
		}
	});

	//user clicks on a contact list to chat with
	$(document).on('click', '.sidebar-chat-list li', '', function(){

		//check if chat box is not already open
		if($('.chat-box-outer[data-username='+$(this).data('username')+']').length <= 0) {
			//check for any other open chat boxes
			//and count them
			var chat_boxes_count = $('.chat-box-outer').length;
			var left_position    = parseInt(parseInt($('.chat-box-outer').width())*parseInt(chat_boxes_count + 1) - 35);

			var chat_body = ''+
				'<div style="left:'+left_position+'px !important;" data-username="'+$(this).data('username')+'" data-fullname="'+$(this).data('fullname')+'" data-id="'+$(this).data('id')+'" class="chat-box-outer">'+
				    '<div class="well chat-box-head nmb">'+
						'<button id="close-chat-box" type="button" class="close" data-dismiss="alert" aria-label="Close">'+
						  '<span aria-hidden="true" class="text-black">&times;</span>'+
						'</button>'+
				        '<div class="media nmt">'+
				          '<div class="media-left hidden">'+
				            '<a href="/profile/'+$(this).data('username')+'">'+
				              '<img id="inbox-whois" class="media-object img-circle" style="width:28px;height:28px;" src="/uploads/thumb_'+$(this).data('pp')+'" alt="...">'+
				            '</a>'+
				          '</div>'+
				          '<div class="media-body">'+
				            '<h5 id="inbox-whois-fullname" class="media-heading fwb nmb">'+$(this).data('fullname')+'</h5>'+
				          '</div>'+
				        '</div>'+
				    '</div>'+
				    '<div class="well chat-box-inner nmb">'+

				    '</div>'+
				    '<div class="well chat-box-well-input">'+
				        '<input class="chat-box-input" placeholder="Type a message..."/>'+
				    '</div>'+
				'</div>';

			$('body').append(chat_body);
			handler_feeds.saveActiveConversation($(this).data('username'), 1);
			handler_feeds.show_conv($(this));
		}
		else{

		}
	});

	//when user clicks on close chat box
	$(document).on('click', '#close-chat-box', '', handler_feeds.close_chat_box);

	//send message handler
	$(document).on('keydown', '.chat-box-input', '', function(e){
		var keyCode = e.keyCode || e.which;
	  if (keyCode == 13) {
			//check for empty msg
			if($('.chat-box-input').val() != ''){
				//get username
				var username = $(this).parents('.chat-box-outer').data('username');
				var fullname = $(this).parents('.chat-box-outer').data('fullname');
				var message  = $(this).val();

				//chat message outbound html
				var chat_message_box = ''+
					'<div class="row margin-bottom-sm">'+
						'<div class="chat-outgoing margin-bottom-md">'+
							'<div class="chat-message-box wa pull-right">'+
								'<div class="chat-out fs15">'+
									message+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';

				comet.send_message({for_username:username, by_username:session_username, by_id:session_id, by_pp:session_pp, by_fullname:session_fullname, message:message}, function(data){
					var chatBody = $('.chat-box-outer[data-username="'+username+'"]').find('.chat-box-inner');
					chatBody.append(chat_message_box);
					chatBody.animate({ scrollTop: chatBody[0].scrollHeight}, 400);
				});

				$('.chat-box-input').val('');
				//$('#typing'+$('#id').val()).remove(); //remove is typing of this user
				handler_feeds.save_conv({message:message, for_username:username});
			}
	  }
	});

	//eof chat workers

	handler_feeds.soundcloud_init();
	handler_feeds.auto_grow();
	handler_feeds.init_photo_grid({gutter: 1});

	//post status
	status_button.click(handler_feeds.share_status);
	text_status.bind('paste', handler_feeds.url_expander);

	//edit post
	$(document).on('click', '.editpost', '', handler_feeds.edit_post);
	$(document).on('click', '#done_editing', '', handler_feeds.save_edit_post);
	$(document).on('click', '#close_edit', '', handler_feeds.close_edit_post);

	//delete post
	$(document).on('click', '.deletepost', '', handler_feeds.delete_post);
	$(document).on('click', '#confirm', '', handler_feeds.confirm_delete_post);

	//unlike and like handler
	$(document).on('click', 'span.like', {type: "1"}, handler_feeds.like_unlike_handler);
	$(document).on('click', 'span.unlike', {type: "2"}, handler_feeds.like_unlike_handler);

	//adding comment
	$(document).on('click', '#comments_btn', '', handler_feeds.openComments);
	$(document).on('keydown', '#comment', '', handler_feeds.addComment);
	//add comments for comment_gallery
	$(document).on('keydown', '#comment_gallery', '', handler_feeds.addComment_gallery);

	//edit comment
	$(document).on('click', 'span.edit-comment', '', handler_feeds.edit_coment_opener);
	$(document).on('click', 'button.comment-edit-closer', '', handler_feeds.edit_coment_closer);
	$(document).on('click', 'button.comment-edit-save', '', handler_feeds.edit_coment_save);
	$(document).on('click', 'span.delete-comment', '', handler_feeds.delete_comment_confirm);
	$(document).on('click', '#confirm_delete_comment', '', handler_feeds.delete_comment);

	//view previous comments
	$(document).on('click', '#view_prev_comm', '', handler_feeds.view_prev_comm);

	//profile completion handler
	next_question.click(handler_feeds.save_answer);
	next_question.click(handler_feeds.skip_answer);

	//image and image status handlers
	photo_select.click(handler_feeds.click_file);
	$(document).on('click', '.upload-next-img-div', '', handler_feeds.click_file);
	photo_upload.change(handler_feeds.submit_photo_upload_form);

	//on click view lightbox for photos
	$(document).on('click', '.feedPhotos', '', handler_feeds.showPhotoAlbum);

	//share post
	$(document).on('click', '.share-btn', '', handler_feeds.sharePost);

	//ajax file upload listner
	photo_upload_form.ajaxForm({
		beforeSend: function() { //before sending form
			$('.next-img-col').remove();
			status_img_div.append('<div class="col-xs-2 loader-col"><div class="upload-progress-div"><div class="progress tiny-progress-bar"><div class="progress-bar progress-bar-striped active upload-progress-div-progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div></div></div>');
		},
		uploadProgress: function(event, position, total, percentComplete) { //on progress
			$('.upload-progress-div-progress-bar').attr('style', 'width:'+percentComplete+'%');
		},
		complete: function(response) { // on complete
			$('.loader-col').remove();
			status_img_div.append('<div class="col-xs-2 img-col"><img class="img-uploads-stdiv" src="/uploads/thumb_'+response.responseText+'"></img></div>');
			status_img_div.append('<div class="col-xs-2 next-img-col"><div class="upload-next-img-div"><i class="fa fa-plus"></i></div>');
			photo_array.push(response.responseText);
		}
	});

	//when user clicks the next image on image gallery load its comments
	// bind the method to Galleria.ready
	Galleria.ready(function(options) {
	    this.bind('image', function(e) { //on next or prev image
			var image = this.getActiveImage().src.split('/')[4];
			input_comment_gallery.attr('data-image', image);
			//send ajax request with the image name and load its comments

			//if more number of images
			if(image_counter > 0){
				var dataString = "image="+image+"&feed_id="+img_feed_id;
			}
			//else load comments of the only image
	   		else if(image_counter == 0){
				var dataString = "image="+image+"&single="+1+"&feed_id="+img_feed_id;
	   		}

			$.ajax({
			    type  : 'POST',
			    url   : '/show_gallery_comments',
			    data  : dataString,
				beforeSend: function(){
					gallery_comments.empty();
					overlay_loader.removeClass('hidden');
					gallery_loader.removeClass('hidden');
				},
				success: function(html){
					gallery_comments.append(html);
				},
				complete: function(responseText){
					overlay_loader.addClass('hidden');
					gallery_loader.addClass('hidden');
					//slim scroll on photo gallery comments
					gallery_comments.slimScroll({
						height: '350px'
					});
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
	    });
	});
}

/* =============================================== HELPERS ==============================================*/

handler_feeds  = {
	scroll_bottom: function(){
		var chat_body = $('.chat-box-outer').find('.chat-box-inner');
		if(chat_body.length)
			chat_body.animate({ scrollTop: chat_body[0].scrollHeight}, 10);
	},
	show_conv: function(self){
		var id  = self.data('id');
		var chat_body = $('.chat-box-outer[data-id='+id+']').find('.chat-box-inner');
		$.ajax({
		    type  : 'POST',
		    url   : '/get_chat_conv',
			data  : { id : id },
			beforeSend: function(){
				chat_body.html('<div class="loader loader-inner ball-pulse text-center" style="margin-top: 100px;"><div></div><div></div><div></div></div>');
			},
			success: function(data){
				if(data){
					chat_body.html(data);
				}
			},
			complete: function(responseText){
				chat_body.animate({ scrollTop: chat_body[0].scrollHeight}, 10);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	save_conv: function(data){
		$.ajax({
		    type  : 'POST',
		    url   : '/save_conv',
			data  : data,
			beforeSend: function(){

			},
			success: function(data){

			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	close_chat_box: function(){
		self = $(this);
		var username = self.parents('.chat-box-outer').data('username');
		self.parents('.chat-box-outer').remove();
		handler_feeds.saveActiveConversation(username, 0);
	},
	saveActiveConversation: function(with_username, type){
		$.ajax({
			type  : 'POST',
			url   : '/save_active_conversation',
			data  : { with_username : with_username, type:type },
			beforeSend: function(){

			},
			success: function(html){

			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown)
			}
		});
	},
	populateOnlineChatList: function(friend_list){
		$.ajax({
			type  : 'POST',
			url   : '/chat_list',
			data  : { friend_list : friend_list },
			beforeSend: function(){

			},
			success: function(html){
				sidebar_chat_list.html(html);
			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown)
			}
		});
	},
	sharePost: function(e){
		var feed_id = $(this).parents('.post-container').data('id');
		var post_card_obj = $(this).parents('.post-container');
		$.confirm({
		    title: 'Share post!',
		    content: 'Would you like to share this post on your wall ?',
		    confirmButton: 'Share',
		    theme: 'supervan',
		    keyboardEnabled: true,
		    confirmButtonClass: 'btn-primary',
		    animation: 'bottom',
		    confirm: function(){
					$.ajax({
					    type  : 'POST',
					    url   : '/share_feed',
					    data  : {feed_id:feed_id},
						beforeSend: function(){
							post_card_obj.find('div#delete_loader').removeClass("hidden");
							post_card_obj.find('div#post_dropdown').addClass("hidden");
						},
						success: function(html){

						},
						complete: function(responseText){
							$.alert({
							    title: 'Success!',
							    content: 'This post has been successfully shared',
							    keyboardEnabled: true,
							    theme: 'supervan'
							});
							post_card_obj.find('div#post_dropdown').removeClass("hidden");
							post_card_obj.find('div#delete_loader').addClass("hidden");

						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
						    alert(errorThrown)
						}
					});
		    }
		});
	},
	addComment_gallery: function(e){
		var keyCode    = e.keyCode || e.which;
		var thisObj    = $(this);
		var dataString = "feed_id="+$(this).data('feed')+"&image="+$(this).data('image')+"&comment="+$(this).val();
		if (keyCode == 13){
			$.ajax({
			    type  : 'POST',
			    url   : '/add_comment',
			    data  : dataString,
				beforeSend: function(){

				},
				success: function(html){
					gallery_comments.append(html);
				},
				complete: function(responseText){
					thisObj.val("");
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	timestampToDate: function(timestamp){
		return jsDate = new Date(timestamp*1000);
	},
	showPhotoAlbum: function(){
		//loop and set image data
		var dom_data    = [];
		var clicked_img = $(this).data('img');
		var thisObj     = $(this);
		img_feed_id     = thisObj.data('id'); //set feed id for retriving comments
		//load feed photo gallery data from feed
		var dataString = 'feed_id=' + $(this).data('id') + '&image=' + clicked_img;
		$.ajax({
		    type     : 'POST',
		    url      : '/get_photo_gallery_data',
		    data     : dataString,
		    dataType : 'json',
			beforeSend: function(){
				$('.img-container').empty();
				gallery_comments.empty();
				image_counter = 0;
			},
			success: function(data){
				var created = handler_feeds.timestampToDate(data.user_data.created).toDateString();
				viewerDialogMediaBody.children('.media-body').find('h4').text(data.user_data.fullname); //setting fullname
				viewerDialogMediaBody.children('.media-left').find('a').attr('href', '/profile/'+data.user_data.username+''); //setting href for profile
				viewerDialogMediaBody.children('.media-left').find('a').children('img').attr('src', '/uploads/'+data.user_data.profile_picture+''); //setting profile picture
				viewerDialogMediaBody.children('.media-body').find('small').text(created) //set the time when post was created

				//set feed id for input comment
				input_comment_gallery.attr('data-feed', thisObj.data('id'));

				//set clicked image as first
				dom_data.push({ image: '/uploads/'+clicked_img });

				$.each(data.images, function(index, data) {
				    dom_data.push({ image: '/uploads/'+data.image });
				});

				image_counter = data.images.length;

			},
			complete: function(responseTet){

				viewerModal.modal('show');
		        Galleria.loadTheme('/public/assets/js/plugins/jqueryImageSlider/themes/classic/galleria.classic.js');

		        Galleria.run('.img-container', {
				    dataSource: dom_data,
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown);
			}
		});
	},
	init_photo_grid: function(options){
		$('.img-collage-div').gridalicious(options);
	},
	submit_photo_upload_form: function(){
		photo_upload_form.submit();
	},
	//for file upload / photo update
	click_file: function(){
		photo_upload.click();
		isPhotoUpdate = true;
	},
	show_next_profile_setup_data: function(column_name){
		var dom = void(0);

		if(column_name === "gender"){
			dom = '<select id="answer" data-name="gender" class="form-control input-sm"><option>Male</option><option>Female</option><option>Other</option></select>';
			question.text('Please specify your gender');
		}
		else if(column_name === "college"){
			dom = '<input id="answer" data-name="college" class="form-control input-sm" placeholder="Example Hadvard University"></input>';
			question.text('Where did you go for college');
		}
		else if(column_name === "location"){
			dom = '<input id="answer" data-name="location" class="form-control input-sm" placeholder="Where are you now living currently ?"></input>';
			question.text('Where are you living ?');
		}
		else if(column_name === "home"){
			dom = '<input id="answer" data-name="home" class="form-control input-sm" placeholder="Example: New York"></input>';
			question.text('Where is your home town ?');
		}
		else if(column_name === "relationship"){
			dom = '<select id="answer" data-name="relationship" class="form-control input-sm"><option>Single</option><option>In a relationship</option><option>Engaged</option><option>Married</option><option>In a civil partnership</option><option>In a domestic partnership</option><option>In an open relationship</option><option>Complicated</option><option>Separated</option><option>Divorced</option><option>Widowed</option></select>';
			question.text('Whats your relationship status');
		}
		else if(column_name === "schooling"){
			dom = '<input id="answer" data-name="schooling" class="form-control input-sm" placeholder="Example Spring Field Highschool"></input>';
			question.text('Where did you went for schooling');
		}
		else{
			//profile complete
		}

		question_div.hide();
		input_content.html(dom);
		question_div.slideDown();
	},
	save_answer: function(){
		var dataString = "answer="+$("#answer").val()+"&question="+$("#answer").data("name");

		if($("#answer").val()){
			$.ajax({
			    type  : 'POST',
			    url   : '/bake_profile',
			    data  : dataString,
				beforeSend: function(){

				},
				success: function(data){
					current_c_perc = parseInt(current_c_perc) + 14;
					question_div.slideUp();
					text_perc_com.text('Your profile is now '+current_c_perc+' % complete');
					progress_bar.attr('style', 'width:'+current_c_perc+'%');
					progress_bar.attr('data-value', current_c_perc);
					if(data) handler_feeds.show_next_profile_setup_data(data);
				},
				complete: function(responseText){

				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	skip_answer: function(){

	},
	view_prev_comm: function(){
		var thisObj    = $(this);
		var feed_id    = $(this).parents('.post-container').data("id");
		var ob         = thisObj.parents('.post-container').find('#comments_btn').text();
		var dat        = ob.split(' ');
		if(exec == 0){
			previous_comment_offset = dat[0] - 6;
			multiples  = parseInt(dat[0]/3);
			remainder  = 3;
			exec       = 1;
		}

		var dataString = "offset="+previous_comment_offset+"&feed_id="+feed_id+"&remainder="+remainder;

		$.ajax({
		    type  : 'POST',
		    url   : '/show_comment',
		    data  : dataString,
			beforeSend: function(){
				previous_comment_offset -= 3;
				multiples --;
				if(multiples == 1){ previous_comment_offset = 0; remainder = dat[0] - multiples * 3;}
				else if(multiples < 1 ){ thisObj.hide(); }
				thisObj.parent().parent().children('.loader').removeClass('hidden');
				thisObj.hide();
			},
			success: function(html){
				thisObj.parent().parent().children('.loader').addClass('hidden');
				thisObj.parent().parent().find('.comments-holder').prepend(html);
				if(multiples > 0) thisObj.show();
			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	delete_comment_confirm: function(){
		utility_modal.modal('show');
		utility_modal.find('h4').html('<b>Delete Comment</b>');
		utility_modal.find('p').text('Are you sure you want to delete this comment ?');
		utility_modal.find('div.model-buttons').find('button#confirm').attr("id", "confirm_delete_comment").attr("data-id", $(this).parents('.comment').data("id"));
		comment_card_obj = $(this).parents('.comment');
	},
	delete_comment: function(){
		var this_obj   = $(this);
		var dataString = "comment_id="+this_obj.data("id");

		if(comment){
			$.ajax({
			    type  : 'POST',
			    url   : '/delete_comment',
			    data  : dataString,
				beforeSend: function(){
					utility_modal.modal('hide');
					comment_card_obj.find('span.delete-comment').attr("data-icon", "").html('<div class="loader loader-inner ball-pulse mrt10"><div></div><div></div><div></div></div>');
				},
				success: function(html){
					comment_card_obj.fadeOut("slow");
				},
				complete: function(responseText){
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	edit_coment_save: function(){
		var this_obj   = $(this);
		var comment    = this_obj.parent().parent().find('input').val();
		var dataString = "comment="+comment+"&comment_id="+this_obj.parents('.comment').data("id");

		if(comment){
			$.ajax({
			    type  : 'POST',
			    url   : '/edit_comment',
			    data  : dataString,
				beforeSend: function(){
					this_obj.parents('div.media-body').children('#comment_edit_loader').removeClass("hidden");
					this_obj.parents('div.media-body').children('div.edit-comment-box').addClass("hidden");
				},
				success: function(html){
					this_obj.parents('.media-body').children('#comment_edit_loader').addClass("hidden");
					this_obj.parents('div.media-body').children('p.comment-data').text(comment).show();
				},
				complete: function(responseText){

				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	edit_coment_opener: function(){
		$(this).parents('div.media-body').children('div.edit-comment-box').removeClass("hidden");
		$(this).parents('div.media-body').children('p.comment-data').hide();
	},
	edit_coment_closer: function(){
		$(this).parents('div.media-body').children('p.comment-data').show();
		$(this).parents('div.media-body').children('div.edit-comment-box').addClass("hidden");
	},
	openComments: function(){
		var this_post_container = $(this).parents('.post-container');
		this_post_container.children('.post-card-footer').addClass('copenpccss');
		this_post_container.children('.comment-container').removeClass("hidden").hide().slideDown();
	},
	addComment: function(e){
		var keyCode    = e.keyCode || e.which;
		var thisObj    = $(this);
		var dataString = "feed_id="+$(this).parents('.post-container').data("id")+"&comment="+$(this).val();
		if (keyCode == 13){
			$.ajax({
			    type  : 'POST',
			    url   : '/add_comment',
			    data  : dataString,
				beforeSend: function(){

				},
				success: function(html){
					thisObj.parents('.comment-container').children('.comments-holder').append(html);
				},
				complete: function(responseText){
					thisObj.val("");
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	like_unlike_handler: function(event){
		var type        = event.data.type;
		var this_obj    = $(this);
		var data_type   = this_obj.data("type");

		if(type == 1 || type == "1"){ //for like handle
			//checking type
			if(data_type == "2" || data_type == 2){ //for comment likes

				var dataString  = "comment_id="+this_obj.parents('.comment').data("id")+"&type=like";

			}else if(data_type == "1" || data_type == 1){ //for post likes

				var dataString  = "feed_id="+this_obj.parents('.post-container').data("id")+"&type=like";
			}
		}
		else if(type == 2 || type == "2"){ //for unlike handle
			//checking type
			if(data_type == "2" || data_type == 2){ //for comment likes

				var dataString  = "comment_id="+this_obj.parents('.comment').data("id")+"&type=unlike";

			}else if(data_type == "1" || data_type == 1){ //for post likes

				var dataString  = "feed_id="+this_obj.parents('.post-container').data("id")+"&type=unlike";
			}
		}

		$.ajax({
		    type  : 'POST',
		    url   : '/unlike_like_post_and_comment',
		    data  : dataString,
			beforeSend: function(){
				if(type == 1 || type == "1"){ //likes
					if(data_type == 1 || data_type == "1"){ //for post
						var like_counter = this_obj.parents('.post-container').find('.like-span').children('b');
						this_obj.removeClass('like').addClass('unlike');
						this_obj.removeClass('fa-heart-o').addClass('fa-heart').addClass('text-heart');
						like_counter.text(parseInt(like_counter.text()) + 1);
					}
					else if(data_type == 2 || data_type == "2"){ //for comments
						this_obj.removeClass('like').addClass('unlike');
						this_obj.addClass('color-primary');
						var lcount = this_obj.children('i').text();
						var res    = lcount.split('');

						if(res[1]){
							if(res[1] >= 1 || res[1] >= "1"){
								var newCount = parseInt(res[1]) + 1;
								this_obj.children('i').text('+'+newCount);
							}
							else{
								this_obj.children('i').text('+1');
							}
						}
						else{
							this_obj.children('i').text('+1');
						}
					}
				}
				else if(type == 2 || type == "2"){ //unlikes
					if(data_type == 1 || data_type == "1"){ //for post
						var like_counter = this_obj.parents('.post-container').find('.like-span').children('b');
						this_obj.removeClass('unlike').addClass('like');
						this_obj.addClass('fa-heart-o').removeClass('fa-heart').removeClass('text-heart');
						like_counter.text(parseInt(like_counter.text()) - 1);
					}
					else if(data_type == 2 || data_type == "2"){ //for comments
						this_obj.removeClass('unlike').addClass('like');
						this_obj.removeClass('color-primary');
						var lcount = this_obj.children('i').text();
						var res    = lcount.split('');

						if(res[1]){
							if(res[1] > 1 || res[1] > "1"){
								this_obj.children('i').text('+'+parseInt(res[1] - 1));
							}
							else{
								this_obj.children('i').text('');
							}
						}
						else{
							this_obj.children('i').text('');
						 }
					}
				}
			},
			success: function(html){

			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	auto_grow: function(){
		text_status.autogrow();
	},
	reset_oembd_data: function(){
		sc_widget.empty();
		video_frame.empty();
	},
	soundcloud_init: function(){
		SC.initialize({
		    client_id: scapiKey
		});
	},
	soundcloud_embed: function(id){
	  SC.oEmbed("http://soundcloud.com/" + id // user or playlist to embed
	    , { color: "ff0066"
	      , auto_play: false
	      , maxheight: 166
	      , show_comments: false
	      , show_user: true } // options
	    , document.getElementById("scWidget") // what element to attach player to
	  );
	},
	soundcloud_trackId: function(track){
		// permalink to a track
		var track_url = track;

		SC.get('/resolve', { url: track_url }, function(track) {
		  SC.get('/tracks/' + track.id + '/comments', function(comments) {
		  	global_SoundCloud_Link = track.id;
		  });
		});
	},
	url_expander: function(e){
		data       = e.originalEvent.clipboardData.getData('Text');
		matches    = data.match(/watch\?v=([a-zA-Z0-9\-_]+)/);
		soundcloud = data.match(/soundcloud\.com/);
		viemo      = data.match(/vimeo\.com/);

		if(matches) //for youtube
		{
			video_frame.removeClass("hidden");
			regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
			match  = data.match(regExp);

			if (match&&match[2].length == 11){
				code = match[2];
			}
			else{
				video_frame.text('Unable to detect the video, please try again');
			}

		    $.ajax({
		        'url'     : 'https://www.googleapis.com/youtube/v3/videos?id='+code+'&key='+apiKey+'&part=snippet&callback=?',
		        'dataType': "json",
				beforeSend: function() {
					loader.removeClass("hidden");
					status_button.attr("disabled","disabled");
				},
		        success: function(data) {
		        	loader.addClass("hidden");
		        	status_button.removeAttr("disabled");
		    		video_frame.find('img').attr("src", data.items[0].snippet.thumbnails.default.url);
		    		video_frame.find('h4').text(data.items[0].snippet.title);
		    		video_frame.find('p').text(data.items[0].snippet.description);
		    		video_frame.find('a').attr("href", "https://www.youtube.com/watch?v="+code+"");
		    		youtube_vcode  = code;
		    		youtube_vthumb = data.items[0].snippet.thumbnails.default.url;
		    		youtube_vdesc  = data.items[0].snippet.description;
		    		youtube_vtitle = data.items[0].snippet.title;
		        }
		    });
		}
		else if(soundcloud){ //for soundcloud
			var res = data.split('.com/',2);
			handler_feeds.soundcloud_embed(res[1]);
			handler_feeds.soundcloud_trackId(data);
			status_button.text('Share this sound');
		}
	},
	share_status: function(){
		//if share status is on a wall then do this
		if($(this).data('id')){
			var wall_id = $(this).data('id');
			var status  = text_status.val().replace(/\r\n|\r|\n/g,"<br />");
			if(status != "" || isPhotoUpdate == true || global_SoundCloud_Link != "" || code != ""){
				//if a youtube video
				if(code){
					var dataString = "content="+status+"&youtube_vcode="+youtube_vcode+"&youtube_vthumb="+youtube_vthumb+"&youtube_vdesc="+youtube_vdesc+"&youtube_vtitle="+youtube_vtitle+"&type="+1+"&isWall="+1+"&wall_id="+wall_id;
				}
				else if(global_SoundCloud_Link){ //if sound cloud
					var dataString = "content="+status+"&soundcloud_code="+global_SoundCloud_Link+"&type="+2+"&isWall="+1+"&wall_id="+wall_id;
				}
				else if(isPhotoUpdate){ //if a photo update
					var dataString = {content: status, photo_array: photo_array, isWall: 1, wall_id: wall_id}
					dataString     = JSON.stringify(dataString);
					$.ajax({
					    type  : 'POST',
					    url   : '/bake_photo_update',
					    data  : {data : dataString},
						beforeSend: function(){
							loader.removeClass("hidden");
							status_button.attr("disabled","disabled");
							handler_feeds.reset_oembd_data();
						},
						success: function(html){
							loader.addClass("hidden");
							$(html).hide().prependTo(feed_content).fadeIn("slow");
							text_status.val('');
							status_button.removeAttr("disabled").text("share");
							video_frame.addClass("hidden");
							isPhotoUpdate = false;
							global_SoundCloud_Link = '';
							code = '';
							status_img_div.empty();
						},
						complete: function(responseText){
							handler_feeds.init_photo_grid({gutter: 1});
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
						    alert(errorThrown)
						}
					});

					return;
				}
				else{
					var dataString = "content="+status+"&type="+0+"&isWall="+1+"&wall_id="+wall_id;
				}

				$.ajax({
				    type  : 'POST',
				    url   : '/share_post',
				    data  : dataString,
					beforeSend: function(){
						loader.removeClass("hidden");
						status_button.attr("disabled","disabled");
						handler_feeds.reset_oembd_data();
					},
					success: function(html){
						loader.addClass("hidden");
						$(html).hide().prependTo(feed_content).fadeIn("slow");
						text_status.val('');
						status_button.removeAttr("disabled").text("share");
						video_frame.addClass("hidden");
						isPhotoUpdate = false;
						global_SoundCloud_Link = '';
						code = '';
					},
					complete: function(responseText){

					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
					    alert(errorThrown)
					}
				});
			}
		}
		else{ //else normal post share
			var wall_id = 0; //reset
			var status = text_status.val().replace(/\r\n|\r|\n/g,"<br />");
			if(status != "" || isPhotoUpdate == true || global_SoundCloud_Link != "" || code != ""){
				//if a youtube video
				if(code){
					var dataString = "content="+status+"&youtube_vcode="+youtube_vcode+"&youtube_vthumb="+youtube_vthumb+"&youtube_vdesc="+youtube_vdesc+"&youtube_vtitle="+youtube_vtitle+"&type="+1+"&isWall="+0+"&wall_id="+wall_id;
				}
				else if(global_SoundCloud_Link){ //if sound cloud
					var dataString = "content="+status+"&soundcloud_code="+global_SoundCloud_Link+"&type="+2+"&isWall="+0+"&wall_id="+wall_id;
				}
				else if(isPhotoUpdate){ //if a photo update
					var dataString = {content: status, photo_array: photo_array, isWall: 0, wall_id: wall_id}
					dataString     = JSON.stringify(dataString);
					$.ajax({
					    type  : 'POST',
					    url   : '/bake_photo_update',
					    data  : {data : dataString},
						beforeSend: function(){
							loader.removeClass("hidden");
							status_button.attr("disabled","disabled");
							handler_feeds.reset_oembd_data();
						},
						success: function(html){
							loader.addClass("hidden");
							$(html).hide().prependTo(feed_content).fadeIn("slow");
							text_status.val('');
							status_button.removeAttr("disabled").text("share");
							video_frame.addClass("hidden");
							isPhotoUpdate = false;
							global_SoundCloud_Link = '';
							code = '';
							status_img_div.empty();
						},
						complete: function(responseText){
							handler_feeds.init_photo_grid({gutter: 1});
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
						    alert(errorThrown)
						}
					});

					return;
				}
				else{
					var dataString = "content="+status+"&type="+0+"&isWall="+0+"&wall_id="+wall_id;
				}

				$.ajax({
				    type  : 'POST',
				    url   : '/share_post',
				    data  : dataString,
					beforeSend: function(){
						loader.removeClass("hidden");
						status_button.attr("disabled","disabled");
						handler_feeds.reset_oembd_data();
					},
					success: function(html){
						loader.addClass("hidden");
						$(html).hide().prependTo(feed_content).fadeIn("slow");
						text_status.val('');
						status_button.removeAttr("disabled").text("share");
						video_frame.addClass("hidden");
						isPhotoUpdate = false;
						global_SoundCloud_Link = '';
						code = '';
					},
					complete: function(responseText){

					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
					    alert(errorThrown)
					}
				});
			}
		}
	},
	edit_post: function(){
		var this_post_card = $(this).parents('.post-container');
		this_post_card.find('textarea').autogrow();
		this_post_card.find('p.mrt10').addClass('hidden');
		this_post_card.find('div#edit_panel').removeClass("hidden");
	},
	close_edit_post: function(){
		var this_post_card = $(this).parents('.post-container');
		this_post_card.find('textarea').text('');
		this_post_card.find('p.mrt10').removeClass('hidden');
		this_post_card.find('div#edit_panel').addClass("hidden");
	},
	save_edit_post: function(){
		var this_post_card = $(this).parents('.post-container');

		var dataString = "feed_id="+this_post_card.data("id")+"&content="+this_post_card.find('textarea').val();
		$.ajax({
		    type  : 'POST',
		    url   : '/edit_post',
		    data  : dataString,
			beforeSend: function(){
				this_post_card.find('div#edit_loader').removeClass("hidden");
				this_post_card.find('div#edit_panel').addClass("hidden");
			},
			success: function(html){
				this_post_card.find('p.mrt10').removeClass('hidden');
				this_post_card.find('p.mrt10').text(this_post_card.find('textarea').val());
				this_post_card.find('div#edit_loader').addClass("hidden");
			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	delete_post: function(){
		utility_modal.modal('show');
		utility_modal.find('h4').html('<b>Delete post</b>');
		utility_modal.find('p').text('Are you sure you want to delete this post ?');
		utility_modal.find('div.model-buttons').find('button#confirm_delete_comment').attr("id", "confirm");
		post_card_obj = $(this).parents('.post-container');
	},
	confirm_delete_post: function(){

		var dataString = "feed_id="+post_card_obj.data("id");
		$.ajax({
		    type  : 'POST',
		    url   : '/delete_post',
		    data  : dataString,
			beforeSend: function(){
				utility_modal.modal('hide');
				post_card_obj.find('div#delete_loader').removeClass("hidden");
				post_card_obj.find('div#post_dropdown').addClass("hidden");
			},
			success: function(html){
				post_card_obj.fadeOut("slow");
			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	}
}



//document ready
$('document').ready(init());
