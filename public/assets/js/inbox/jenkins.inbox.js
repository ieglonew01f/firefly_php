//Inbox handler

//cache
var chatter_inbox        = $("#chatter-inbox");
var inbox_whois          = $("#inbox-whois");
var inbox_fullname       = $("#inbox-whois-fullname");
var comet_input_inbox    = $('.chatter-input-comet');
var jenkins_session_data = $('#jenkins_session_data');
var contact_list_inbox   = $('.contact-list-inbox');
var new_message_btn      = $('.new-message-inbox');
var utility_modal        = $('#total_utility_modal');

//variables
var session_username = jenkins_session_data.data('username');
var session_fullname = jenkins_session_data.data('fullname');
var session_id       = jenkins_session_data.data('id');
var session_pp       = jenkins_session_data.data('pp');
var isTypingSent     = false;
var noIsTyping       = false;

//init function
var init = function(){
	//workers
	chatter_inbox.slimScroll({
		height: '600px',
		start: 'bottom'
	});

	$(document).on('click', '.contact', '', inbox_handler.show_conv);

	//chat workers
	//register on node.js network
	comet.register({'id':session_id, 'username': session_username, 'fullname': session_fullname});

	//recieve message handler
	comet.new_message(function(data){

		$('#isTypingRow').remove(); //remove istyping
		isTypingSent = false;

		//building html
		var html = '<div class="row margin-bottom-sm"><div class="message-incomming"><div class="message-box"><div class="row"><div class="col-md-2"><img class="media-object img-circle" data-src="holder.js/64x64" alt="64x64" src="/uploads/'+data.by_pp+'" data-holder-rendered="true" style="width: 54px; height: 54px;"></div><div class="col-md-10 wa"><div class="message-in">'+data.message+'</div></div></div></div></div></div>';
		chatter_inbox.append(html);
		chatter_inbox.animate({ scrollTop: chatter_inbox[0].scrollHeight}, 400);

	});

	//refresh chat list if new user
	//send friendlist to socket server and check who is online and get list of friends online
	comet.chat_list(friend_list, function(friends_online){
		//make ajax call to get friends_online details
		if(friends_online.length > 0){
			//handler_feeds.populateOnlineChatList(friends_online);
		}
	});

	//set is typing 
	comet.set_typing(function(data){
		var html = '<div id="isTypingRow" class="row margin-bottom-sm"><div class="message-incomming"><div class="message-box"><div class="row"><div class="col-md-2"><img class="media-object img-circle" data-src="holder.js/64x64" alt="64x64" src="/uploads/'+data.by_pp+'" data-holder-rendered="true" style="width: 54px; height: 54px;"></div><div class="col-md-10 wa"><div class="message-in">is typing....</div></div></div></div></div></div>';
		chatter_inbox.append(html);
		chatter_inbox.animate({ scrollTop: chatter_inbox[0].scrollHeight}, 400);
	});

	//set is not typing 
	comet.set_not_typing(function(data){
		$('#isTypingRow').remove();
	});

	//send message handler
	$(document).on('keyup', '.chatter-input-comet', '', function(e){
		var username = $(this).data('username');

		if(!isTypingSent){
			//send isTyping
			inbox_handler.isTyping(true, {for_username:username, by_username:session_username, by_pp:session_pp, by_fullname:session_fullname});
		}

		if($(this).val() == ''){
			if(!noIsTyping){
				inbox_handler.isTyping(false, {for_username:username, by_username:session_username, by_pp:session_pp, by_fullname:session_fullname});
			}
		}

		var keyCode = e.keyCode || e.which;
	  	if (keyCode == 13) {
			//check for empty msg
			if($(this).val() != ''){
				//get username
				var fullname = $(this).data('fullname');
				var message  = $(this).val();

				//building html
				var html = '<div class="row margin-bottom-sm"><div class="message-outgoing margin-bottom-md"><div class="message-box wa pull-right"><div class="message-out">'+message+'</div></div></div></div>';

				comet.send_message({for_username:username, by_username:session_username, by_pp:session_pp, by_fullname:session_fullname, message:message}, function(data){
					chatter_inbox.append(html);
					chatter_inbox.animate({ scrollTop: chatter_inbox[0].scrollHeight}, 400);
				});

				//send ajax call to save to db
				inbox_handler.save_conv({message:message, for_username:username});
				$(this).val('');
				emojify.run();
			}
	  	}
	});

	//eof chat workers

	//search conv
	$('.search-conv').keyup(inbox_handler.conv_search_handler);

	//new message inbox click
	new_message_btn.click(inbox_handler.new_message_handler);

}

//helpers
var inbox_handler  = {
	new_message_handler: function(){
		utility_modal.find('.modal-title').html('<span data-icon="&#xe095;"></span> New Message');
		utility_modal.modal('show').find('.modal-dialog').attr('style', 'max-width: 400px;').find('.modal-body').attr('style', 'padding:0;');
	},
	conv_search_handler: function(){
		var keyword = $(this).val();
		//if(keyword.length > 3){
			$.ajax({
			    type  : 'POST',
			    url   : '/search_conv',
			    data  : { keyword : keyword },
				beforeSend: function(){
					contact_list_inbox.html('<div class="loader loader-inner ball-pulse text-center" style="margin-top: 100px;"><div></div><div></div><div></div></div>');
				},
				success: function(data){
					contact_list_inbox.html(data);
				},
				complete: function(responseText){

				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		//}
	},
	isTyping: function(flag, data){
		if(flag){
			comet.is_typing(data, function(){
				isTypingSent = true;
			});
		}
		else{
			comet.no_is_typing(data, function(){
				noIsTyping = true;
			});
		}
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
	show_conv: function(){
		var id       = $(this).data('id');
		var src      = $(this).find('.media-object').attr('src');
		var fullname = $(this).find('#media-heading').text();
		var username = $(this).data('username');

		$('.contact').attr('style', '');
		$(this).attr('style', 'border-right: 2px solid #4F93BE;');

		//setting data for Input
		comet_input_inbox.attr('data-fullname', fullname);
		comet_input_inbox.attr('data-username', username);

		//setting profile profile_picture
		inbox_whois.attr('src', src);
		inbox_fullname.text(fullname);

		$.ajax({
		    type  : 'POST',
		    url   : '/get_conv',
				data  : { id : id },
				beforeSend: function(){
					chatter_inbox.html('<div class="loader loader-inner ball-pulse text-center" style="margin-top: 100px;"><div></div><div></div><div></div></div>');
				},
				success: function(data){
					if(data){
							chatter_inbox.html(data);
					}
					else{
							chatter_inbox.html('<div class="allcaughtup text-center" style="margin-top: 85px;"><b><span class="icon-bulb"></span> Nothing to see here</b></div>');
					}
				},
				complete: function(responseText){
					chatter_inbox.animate({ scrollTop: chatter_inbox[0].scrollHeight}, 100);
					emojify.run();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
		});
	}
}



//document ready
$(document).ready(init());
