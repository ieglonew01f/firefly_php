//cache
var status_button  = $("#status_button");
var text_status    = $("#status");
var edit_text      = $("#edit_text");
var loader         = $("#loader");
var feed_content   = $("#feeds_cont");
var video_frame    = $("#video_frame");
var edit_post      = $("li.editpost");
var delete_post    = $("li.deletepost");
var post_card      = $("div.post-container");
var edit_panel     = $("#edit_panel");
var save_editing   = $("#done_editing");
var sc_widget      = $("#scWidget");
var close_edit     = $("#close_edit");
var utility_modal  = $("#utility_modal");
var confirm        = $("#confirm");
var comments_btn   = $("#comments_btn");
var skip_question  = $("#skip_question");
var next_question  = $("#next_question");
var question_div   = $(".question-container");
var question       = $("div.question-bank p");
var input_content  = $("div.input-container");
var answer         = $("#answer");
var text_perc_com  = $("h4.complete-prec-txt");
var progress_bar   = $("div.progress-bar");
var current_c_perc = $('div.progress-bar').data('value');
var profile_c_div  = $('div.profile-completion-panel');

//variables
var data, matches, soundcloud, viemo, code, regExp, match, previous_comment_offset = 6, current_c_perc;
var youtube_vtitle, youtube_vdesc, youtube_vthumb, youtube_vcode, global_SoundCloud_Link, comment_card_obj, post_card_obj,exec = 0, multiples, remainder;

/* SET API KEY FOR YOUTUBE */
var apiKey = "AIzaSyDLAPqnX5JQ6bqcxZJaxrlaFRLqCQMBZQM";
/* SET API KEY FOR SOUND CLOUD*/
var scapiKey = "243727134d2c71ba214ef1ec60a371d3";

//init function
var init = function(){
	//workers
	handler.soundcloud_init();
	handler.auto_grow();

	//post status
	status_button.click(handler.share_status);
	text_status.bind('paste', handler.url_expander);

	//edit post
	$(document).on('click', '.editpost', '', handler.edit_post);
	$(document).on('click', '#done_editing', '', handler.save_edit_post);
	$(document).on('click', '#close_edit', '', handler.close_edit_post);

	//delete post
	$(document).on('click', '.deletepost', '', handler.delete_post);
	$(document).on('click', '#confirm', '', handler.confirm_delete_post);

	//unlike and like handler
	$(document).on('click', 'span.like', {type: "1"}, handler.like_unlike_handler);
	$(document).on('click', 'span.unlike', {type: "2"}, handler.like_unlike_handler);

	//adding comment
	$(document).on('click', '#comments_btn', '', handler.openComments);
	$(document).on('keydown', '#comment', '', handler.addComment);

	//edit comment
	$(document).on('click', 'span.edit-comment', '', handler.edit_coment_opener);
	$(document).on('click', 'button.comment-edit-closer', '', handler.edit_coment_closer);
	$(document).on('click', 'button.comment-edit-save', '', handler.edit_coment_save);
	$(document).on('click', 'span.delete-comment', '', handler.delete_comment_confirm);
	$(document).on('click', '#confirm_delete_comment', '', handler.delete_comment);

	//view previous comments
	$(document).on('click', '#view_prev_comm', '', handler.view_prev_comm);

	//profile completion handler
	next_question.click(handler.save_answer);
	next_question.click(handler.skip_answer);

}

//helpers
var handler  = {
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
					if(data) handler.show_next_profile_setup_data(data);
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
	edit_coment_closer(){
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
						this_obj.removeClass('like').addClass('unlike');
						this_obj.removeClass('fa-heart-o').addClass('fa-heart').addClass('text-heart');
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
						this_obj.removeClass('unlike').addClass('like');
						this_obj.addClass('fa-heart-o').removeClass('fa-heart').removeClass('text-heart');
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
			handler.soundcloud_embed(res[1]);
			handler.soundcloud_trackId(data);
			status_button.text('Share this sound');
		}
	},
	share_status: function(){
		if(text_status != ""){
			//if a youtube video
			if(code){
				var dataString = "content="+text_status.val()+"&youtube_vcode="+youtube_vcode+"&youtube_vthumb="+youtube_vthumb+"&youtube_vdesc="+youtube_vdesc+"&youtube_vtitle="+youtube_vtitle+"&type="+1;		
			}
			else if(global_SoundCloud_Link){ //if sound cloud
				var dataString = "content="+text_status.val()+"&soundcloud_code="+global_SoundCloud_Link+"&type="+2;	
			}
			else{
				var dataString = "content="+text_status.val()+"&type="+0;
			}
			$.ajax({
			    type  : 'POST',
			    url   : '/share_post',
			    data  : dataString,
				beforeSend: function(){
					loader.removeClass("hidden");
					status_button.attr("disabled","disabled");
					handler.reset_oembd_data();
				},
				success: function(html){
					loader.addClass("hidden");
					$(html).hide().prependTo(feed_content).fadeIn("slow");
					text_status.val("");
					status_button.removeAttr("disabled").text("share");
					video_frame.addClass("hidden");
				},
				complete: function(responseText){ 

				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
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