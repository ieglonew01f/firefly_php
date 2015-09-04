//cache
var signup_btn = $('.signup');


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
}

//helpers
var index_handler  = {
	signup: function(){
		var self = $(this);
		if($('.email').val()){
			if(index_handler.isEmail($('.email').val()) == true){
				var email = $('.email').val();
				$.ajax({
					type     : 'POST',
					url      : '/signup_with_email',
					data     : { email:email },
					dataType : 'json',
					beforeSend: function(){
					 	$('i.mail').addClass('loading');
					},
					success: function(response){
						if(response.code.length){
							window.location = '/signup/'+response.code;
						}
					},
					complete: function(data){
					 	$('i.mail').removeClass('loading');
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						 alert(errorThrown)
					}
			 	});
			}
			else{ //no valid email
				alert('Invalid email address');
			}
		}	
	},
	isEmail: function(email){
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
}



//document ready
$('document').ready(init());
