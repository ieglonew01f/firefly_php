//people handler
/* type = 01  -> add friend / request sent
	 type = 11  -> confirm friendship
	 type = 011 -> reject friend request
	 type = 10  -> cancle friend request

	 type = 2   -> follow target id
	 type = 3   -> unfollow target id
*/

//cache
var friend_button    = $(".button_friend");
var btn_gp           = $(".btn-group");
var friendliText     = $(".friendliText");
var result_container = $('.search-results-container');
var search_bar_input = $('.search-bar');
//variables


//init function
var init = function(){

	//friendship handler
	$(document).on('click', '.friend', '', handler.do_people); //do people ;)
	$(document).on('click', 'button.follow', '', handler.do_people);

	//search bar handler
	$('.search-bar').keyup(function(){
		if($(this).val()){
			handler.search_bar_handler($(this).val());
		}
	});
	//handler.search_bar_handler();
}

//helpers
var handler  = {
	search_bar_handler: function(keyword){
		$.ajax({
		    type  : 'POST',
		    url   : '/search_people',
		    data  : { keyword : keyword },
			beforeSend: function(){
				result_container.html('<div class="loader loader-inner ball-pulse text-center" style="margin-top: 100px;"><div></div><div></div><div></div></div>');
			},
			success: function(data){
				if(data){
						result_container.html(data);
				}
				else{
						result_container.html('<div class="allcaughtup text-center" style="margin-top: 85px;"><b><span class="icon-bulb"></span> Nothing to see here</b></div>');
				}
			},
			complete: function(responseText){
				//workers
				result_container.slimScroll({
					height: '220px'
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
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
