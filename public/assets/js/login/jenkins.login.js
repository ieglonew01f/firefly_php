//cache
var login_form    = $(".login_form");
var login_btn     = $("button#login");
var failed_msg    = $("div#alert_login_failed");

//init function
var init = function(){
	handler.preventDefault();
	handler.validate_login();
	//workers
	login_btn.click(handler.new_login);
}

//helpers
var handler  = {
	//validate login
	validate_login: function(){
	    login_form.validate({
	        rules: {
	            email_username: {
	                required: true
	            },
	            password: {
	                required: true
	            }
	        },
	        messages: {
	          email_username: { required: "Please enter your username or email address"},
	          password: { required: "Please enter your password"},
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
	new_login: function(){
		login_form.valid();

		if(login_form.valid()){
			var dataString = login_form.serialize();
			$.ajax({
			    type  : 'POST',
			    url   : '/newLogin',
			    data  : dataString,
				beforeSend: function(){
					failed_msg.addClass("hidden");
				},
				success: function(responseText){
					if(responseText === "0" || responseText === 0){
						failed_msg.removeClass("hidden")
					}
					else{
						window.location = '/home';
						failed_msg.addClass("hidden");
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
		login_form.submit(function(event){
		    event.preventDefault();
		});
	}
}



//document ready
$('document').ready(init());
