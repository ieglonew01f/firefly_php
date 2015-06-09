//cache
var friend_button  = $(".button_friend");
var btn_gp         = $(".btn-group");


//variables



//init function
var init = function(){
	//workers

	//friendship handler
	$(document).on('click', '.friend', '', handler.do_people); //do people ;)
	$(document).on('click', 'button.follow', '', handler.do_people) //;) 

}

//helpers
var handler  = {
	do_people: function(){
		var this_var   = $(this);
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
				else if(this_var.data('type') == '2' || this_var.data('type') == 2){ //follow 
					this_var.text('Following').data('type', 3); 
				}
				else if(this_var.data('type') == '3' || this_var.data('type') == 3){ //unfollow
					this_var.text('Follow').data('type', 2); 
				}
			},
			success: function(data){

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
$(document).ready(init());