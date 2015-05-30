//cache
var status_button = $("button#status_button");
var text_status   = $("textarea#status");
var edit_text     = $("textarea#edit_text");
var loader        = $("div#loader");
var feed_content  = $("div#feeds_cont");
var video_frame   = $("#video_frame");
var edit_post     = $("li.editpost");
var delete_post   = $("li.deletepost");
var post_card     = $("div.post-container");
var edit_panel    = $("#edit_panel");
var save_editing  = $("button#done_editing");
var sc_widget     = $("div#scWidget");
var close_edit    = $("button#close_edit");
var utility_modal = $("div#utility_modal");
var confirm       = $("button#confirm");
var comments_btn  = $("span#comments_btn");

//variables
var data, matches, soundcloud, viemo, code, regExp, match;
var youtube_vtitle, youtube_vdesc, youtube_vthumb, youtube_vcode, global_SoundCloud_Link, post_card_obj;

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
	$(document).on('click', 'span#comments_btn', '', handler.openComments);
	$(document).on('keydown', 'input#comment', '', handler.addComment);

}

//helpers
var handler  = {
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
								this_obj.children('i').text('+'+parseInt(res[1] + 1));
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