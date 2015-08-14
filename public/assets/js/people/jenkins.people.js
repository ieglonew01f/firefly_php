//people handler
/* type = 01  -> add friend / request sent
	 type = 11  -> confirm friendship
	 type = 011 -> reject friend request
	 type = 10  -> cancle friend request

	 type = 2   -> follow target id
	 type = 3   -> unfollow target id
*/
//cache
var friend_button  = $(".button_friend");
var btn_gp         = $(".btn-group");
var friendliText   = $(".friendliText");


//variables


//init function
var init = function(){
	//workers
	$('.search-bar').typeahead({
	  minLength: 3,
	  highlight: true
	},
	{
	  name: 'my-dataset',
	  source: {'rahulkumar':'avc'}
	});
	//friendship handler
	$(document).on('click', '.friend', '', handler.do_people); //do people ;)
	$(document).on('click', 'button.follow', '', handler.do_people);
}

//helpers
var handler  = {
	do_people: function(event){
		event.stopPropagation();
		var this_var   = $(this);
		var isOrigin   = void(0);
		var dataString = "type="+$(this).data("type")+"&profile_id="+$(this).data("id");
		$.ajax({
		    type  : 'POST',
		    url   : '/people_handler',
		    data  : dataString,
			beforeSend: function(){
				if(this_var.data('type') == '01'){
					friend_button.html('Friend request sent <span class="caret"></span>').attr('data-toggle', 'dropdown').blur();
				}
				else if(this_var.data('type') == '10'){
					friend_button.html('Add as a friend').attr('data-toggle', '').blur();
					btn_gp.removeClass('open');
				}
				else if(this_var.data('type') == '11'){ //click on accept friend request
					//do this only if not a notification origin
					if(this_var.data('origin')) {
						isOrigin = 1;
						this_var.parents('.notif-mediabody').html('<div class="loader loader-inner ball-pulse notif-bar-loader-friend-req" style="margin-top:8px;"><div></div><div></div><div></div></div>');
					} else {
						friend_button.html('Friends <span class="caret"></span>').attr('data-toggle', 'dropdown').blur();
						friendliText.text('Unfriend');
						friendliText.parents('a').attr('data-type', '00');
					}
				}
				else if(this_var.data('type') == '22'){ //reject friend request
					//do this only if not a notification origin
					if(this_var.data('origin')) {
						isOrigin = 1;
						this_var.parents('.notif-mediabody').html('<div class="loader loader-inner ball-pulse notif-bar-loader-friend-req" style="margin-top:8px;"><div></div><div></div><div></div></div>');
					} else {
						//to add for profiles
					}
				}
				else if(this_var.data('type') == '00'){ //remove from friend list
					//set to add friend button
					friend_button.parents('.btn-group').find('button').attr('data-type', '01').addClass('friend').attr('data-toggle', '').html('Add as a friend');
					friend_button.parents('.btn-group').removeClass('open');
				}
				else if(this_var.data('type') == '2' || this_var.data('type') == 2){ //follow
					this_var.text('Following').data('type', 3);
				}
				else if(this_var.data('type') == '3' || this_var.data('type') == 3){ //unfollow
					this_var.text('Follow').data('type', 2);
				}
			},
			success: function(data){
				if(isOrigin == '1' || isOrigin == 1){
						$('.notif-bar-loader-friend-req').parents('.li-notif').fadeOut('slow');
				}
			},
			complete: function(responseText){
				isOrigin = 0;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	}
}



//document ready
$(document).ready(init());
