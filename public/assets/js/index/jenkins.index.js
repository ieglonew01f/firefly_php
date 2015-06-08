//cache
var signup_form    = $("form#signup_form");
var signup_btn     = $("button#new_account");
var form_all       = $("form");
var email_input    = $("input#email");
var username       = $("input#username");
var fullname_input = $("[name='fullname']");
var password_input = $("[name='password']");
var username_label = $("#label_username");
var username_test  = false;
var count          = 0;
var fullname_max_length, fullname_min_length, password_max_length, password_min_length;

//init function
var init = function(){
	handler.load_index_settings() //load settings
	handler.validate_signup();
	handler.preventDefault();
	//workers
	signup_btn.click(handler.new_account);
	username.blur(handler.check_username);
}

//helpers
var handler  = {
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

				var password = {
				    minlength: data.password_min_length,
				    maxlength: data.password_max_length,
				    messages: {
				    	minlength: "Password is too short",
				    	maxlength: "Password is too long"
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
				password_input.rules('add', password);
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
	//validate signup
	validate_signup: function(){
	    signup_form.validate({
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
	            password: {
	                required: true
	            },
	            repassword: {
	            	required: true,
					equalTo: "#password"
	            }
	        },
	        messages: {
	          fullname: { required: "Please enter your fullname"},
	          username: { required: "Please enter your username"},
	          email: { required: "Please enter your email"},
	          password: { required: "Please enter password", minlength: "Password is too short, must be 6 char long", maxlength: "Password length limit exceeded"},
	          repassword: { required: "Please enter your password again"}
	        },
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
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
					username_label.text('This username is not available').addClass("text-danger");
					username_test = false;
				}
				else{
					username_test = true;
					username.parent().removeClass("has-error");
					username_label.text('Username').removeClass("text-danger");
				}
			},
			complete: function(responseText){ 
				
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			    alert(errorThrown)
			}
		});
	},
	new_account: function(){
		signup_form.valid();

		if(signup_form.valid() && username_test == true){
			var dataString = signup_form.serialize();
			$.ajax({
			    type  : 'POST',
			    url   : '/signup',
			    data  : dataString,
				beforeSend: function(){
					email_input.parent().removeClass("has-error").find('span').text('Email').removeClass("text-danger");
				},
				success: function(responseText){ 
					if(responseText === "0" || responseText === 0){
						email_input.parent().addClass("has-error").find('span').text('This email address is already registered with us').addClass("text-danger");
					}
					else{
						window.location = '/home';
						username_test = false;
					}
				},
				complete: function(responseText){ 
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				    alert(errorThrown)
				}
			});
		}
	},
	preventDefault: function(){
		form_all.submit(function(event){
		    event.preventDefault();
		});
	}
}



//document ready
$('document').ready(init());