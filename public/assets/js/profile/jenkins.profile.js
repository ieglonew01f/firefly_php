//cache
var change_banner_btn  = $('.change-banner');
var file_change_banner = $('#file_banner');
var banner_form        = $('form.banner_form');
var banner_container   = $('div.well-banner');
var banner_loader      = $('div.banner-loader');
var handler            = {};

//variables



//init function
var init = function(){
	//workers
	change_banner_btn.click(handler.click_file);
	file_change_banner.change(handler.change_banner);

	//ajax file upload listner
	banner_form.ajaxForm({
		beforeSend: function() { //before sending form
			banner_loader.show();
		},
		uploadProgress: function(event, position, total, percentComplete) { //on progress
			banner_loader.find('span.text').text(' uploading... '+percentComplete+' %');
		},
		complete: function(response) { // on complete
			banner_loader.hide();
			banner_container.attr('style','background:url("../uploads/'+response.responseText+'");background-size:cover;');
			//alert(response.responseText);
		}
	});
}

//helpers
handler  = {
	click_file: function(){
		file_change_banner.click();
	},
	change_banner:function(){
		banner_form.submit();
	},
	temp: function(){

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