//notification handler
//cache
var notification_container = $(".notifications-container");
var notifications_dropdown = $('.notifications-dropdown-main');
var notif_badge            = $('.notif-badge');

//variables


//init function
var init = function(){
	//workers
	notification_container.slimScroll({
		height: '220px'
	});

	//get notifications when notifications dropdown opens
	notifications_dropdown.on('show.bs.dropdown', notif_handler.get_notifications);
}

//helpers
var notif_handler  = {
	get_notifications: function(){
		$.ajax({
		    type  : 'GET',
		    url   : '/get_notifications',
			beforeSend: function(){
				notif_badge.text('');
				notification_container.html('<div class="loader loader-inner ball-pulse text-center" style="margin-top: 100px;"><div></div><div></div><div></div></div>');
			},
			success: function(html){
				if(html){
						notification_container.html(html);
				}
				else{
						notification_container.html('<div class="allcaughtup text-center" style="margin-top: 85px;"><b><span class="icon-bulb"></span> Nothing to see here</b></div>');
				}
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
