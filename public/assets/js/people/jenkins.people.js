//cache
var friend_button  = $(".button_friend");
var btn_gp         = $(".btn-group");


//variables



//init function
var init = function(){
	//workers

	//friendship handler
	$(document).on('click', '.friend', '', handler.do_people); //do people ;)

}

//helpers
var handler  = {
	do_people: function(){
		var this_var   = $(this);
		var dataString = "type="+$(this).data("type")+"&profile_id="+$(this).data("id");
		$.ajax({
		    type  : 'POST',
		    url   : '/friendship_handler',
		    data  : dataString,
			beforeSend: function(){
				if(this_var.data('type') == '01')
					friend_button.html('Friend request sent <span class="caret"></span>').attr('data-toggle', 'dropdown').blur();

				else if(this_var.data('type') == '10'){
					friend_button.html('Add as a friend').attr('data-toggle', '').blur();
					btn_gp.removeClass('open');
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