//cache
var signup_btn    = $('#signup');
var fullname      = $('#fullname');
var email         = $('#email');
var password      = $('#password');
var form_valid    = true;
var error_message = $('#error-message');

//init function
var init = function(){
  	// fix menu when passed
	$('.masthead').visibility({
		once: false,
		onBottomPassed: function() {
			$('.fixed.menu').transition('fade in');
		},
		onBottomPassedReverse: function() {
			$('.fixed.menu').transition('fade out');
		}
	});

	// create sidebar and attach to menu open
	$('.ui.sidebar').sidebar('attach events', '.toc.item');

	//click even listners
	signup_btn.click(index_handler.signup);

	$('#fullname, #email, #password')
	  .popup({
	    on       : 'focus',
		position : 'left center'
	});
}

//helpers
var index_handler  = {
	validate_inputs: function(){
		if($('#fullname').val() == ''){
			$('#fullname').parent().addClass('error');
			form_valid = false;
		}
		else{
			$('#fullname').parent().removeClass('error');
		}

		if($('#email').val() == ''){
			$('#email').parent().addClass('error');
			form_valid = false;
		}
		else{
			$('#email').parent().removeClass('error');
		}

		if($('#password').val() == ''){
			$('#password').parent().addClass('error');
			form_valid = false;
		}
		else{
			$('#password').parent().removeClass('error');
		}

		if(!index_handler.isEmail($('#email').val())){
			$('#email').parent().addClass('error');
			form_valid = false;
		}
		else{
			$('#password').parent().removeClass('error');
		}


		return form_valid;
	},
	signup: function(){
		var fullname = $('#fullname').val();
		var email    = $('#email').val();
		var password = $('#password').val();

		if(index_handler.validate_inputs()){
			$.ajax({
				type     : 'POST',
				url      : '/easySignup',
				data     : { fullname : fullname, email: email, password: password  },
				dataType : 'json',
				beforeSend: function(){
					error_message.text('').addClass('hidden');
				},
				success: function(data){
					if(data.status == 'error'){
						error_message.text(data.error_message).removeClass('hidden');
					}
					else{
						window.location = '/home';
					}
				},
				complete: function(data){
					form_valid = true;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown)
				}
		 	});
		}	
	},
	isEmail: function(email){
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
}



//document ready
$('document').ready(init());
