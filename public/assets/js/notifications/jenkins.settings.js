//Settings handler
//cache


//variables


//init function
var init = function() {
	//workers
	$('.settings-options a').click(function(){ 
		var id = $(this).attr('id');
		//$('col-md-9').find('div.panel').addClass('hidden');
		$('#settings_'+id+'').removeClass('hidden');
		console.log($('#settings_'+id+''))
	);

}

//helpers
var settings_handler  = {


	get_notifications: function(){
		$.ajax({
		    type  : 'GET',
		    url   : '/get_notifications',
			beforeSend: function(){
				notif_badge.text('');
				notification_container.html('<div class="loader loader-inner ball-pulse text-center" style="margin-top: 85px;"><div></div><div></div><div></div></div>');
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
