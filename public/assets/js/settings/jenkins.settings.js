//Settings handler
//cache
var settings_menu  = $('.settings-options a');
var settings_form  = $('form#settings_form');
var email_input    = $("input#email");
var username       = $("input#username");
var fullname_input = $("[name='fullname']");
var password_input = $("[name='password']");
var username_label = $("#label_username");
var save_settings  = $("#save_settings");
var small_ulabel   = $('#edit_username_small');
var alert_settings = $('#alert-settings');
var username_test  = false;
var count          = 0;

//variables
var fullname_max_length, fullname_min_length, password_max_length, password_min_length;

//init function
var init = function() {
	//workers
	settings_menu.click(function(){ 
		var self = $(this);
		var id   = self.attr('id');
		$('.col-md-3').find('.settings-options a').removeClass('active');
		self.addClass('active');
		$('.col-md-9').find('div.panel').addClass('hidden');
		$('#settings_'+id+'').removeClass('hidden');
	});

	settings_handler.preventDefault();
	settings_handler.load_index_settings() //load settings
	settings_handler.validate_settings();
	username.blur(settings_handler.check_username);
	save_settings.click(settings_handler.new_settings);
}

//helpers
var settings_handler  = {
	load_index_settings:function(){
		$.ajax({
		    type    : 'GET',
		    url     : '/load_settings_index',
		    dataType: 'json',
			beforeSend: function(){

			},
			success: function(data){
				var fullname = {
				    minlength: data.fullname_min_length,
				    maxlength: data.fullname_max_length,
				    messages: {
				    	minlength: "Fullname is too short",
				    	maxlength: "Fullname is too long"
					}
				}

				var username_r = {
				    minlength: data.username_min_length,
				    maxlength: data.username_max_length,
				    messages: {
				    	minlength: "Username is too short",
				    	maxlength: "Username is too long"
					}
				}

				fullname_input.rules('add', fullname);
				username.rules('add', username_r);
				count = 0;
			},
			complete: function(data){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    //try again
			    if(count < 3){
			    	handler.load_index_settings;
			    	count ++;
			    }
			}
		});
	},
	//validate login
	validate_settings: function(){
	    settings_form.validate({
	        rules: {
	            fullname: {
	                required: true
	            },
	            username: {
	                required: true
	            },
	            email: {
	            	email: true,
	                required: true
	            },
	            college: {
	                required: true
	            }
	        },
	        messages: {
	          fullname: { required: "Please enter your fullname"},
	          username: { required: "Please enter your username"},
	          username: { required: "Please enter your college"}
	        },
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	            $(element).closest('.form-group').find('small').addClass('hidden');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
	            $(element).closest('.form-group').find('small').removeClass('hidden');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block',
	        errorPlacement: function(error, element) {
	            if(element.parent('.input-group').length) {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        }
	    });
	},
	check_username: function(){
		$.ajax({
		    type  : 'POST',
		    url   : '/check_username',
		    data  : "username="+username.val(),
			beforeSend: function(){
				username.parent().removeClass("has-error");
				username_label.text('Username').removeClass("text-danger");
			},
			success: function(responseText){
				if(responseText === "0" || responseText === 0){
					username.parent().addClass("has-error");
					small_ulabel.addClass('hidden');
					username_label.text('This username is not available').addClass("text-danger").removeClass('hidden');
					username_test = false;
				}
				else{
					username_test = true;
					small_ulabel.removeClass('hidden');
					username.parent().removeClass("has-error");
					username_label.addClass('hidden').removeClass("text-danger");
				}
			},
			complete: function(responseText){

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	new_settings: function(){
		var self = $(this);
		settings_form.valid();

		if(settings_form.valid()){
			var dataString = settings_form.serialize();

			$.ajax({
			    type  : 'POST',
			    url   : '/save_settings',
			    data  : dataString,
				beforeSend: function(){
					self.button('loading');
				},
				success: function(responseText){
					alert_settings.removeClass('hidden');
				},
				complete: function(responseText){
					self.button('reset');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	preventDefault: function(){
		settings_form.submit(function(event){
		    event.preventDefault();
		});
	}
}



//document ready
$(document).ready(init());
