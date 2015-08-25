//cache
var signup_form = $("form#signup_form");
var signup_btn  = $("button#new_account");
var form_all    = $("form");

//init function
var init = function(){
	handler.validate_signup();
	handler.preventDefault();
	//workers
	signup_btn.click(handler.new_account);
}

//helpers
var handler  = {
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
	          password: { required: "Please enter password"},
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
	new_account: function(){
		
	},
	preventDefault: function(){
		form_all.submit(function(event){
		    event.preventDefault();
		});
	}
}



//document ready
$('document').ready(init());