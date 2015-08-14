//home handler
//cache
var feed_container = $('div#feeds_cont');

//variables
var offset = 0, endOfFeed = false;

//init function
var init = function(){
	//load more feeds handler
	//load more feeds on scroll bottom
	$(window).scroll(function() {
	 if($(window).scrollTop() + $(window).height() == $(document).height()) {
		 handler_home.loadMoreFeeds();
	 }
	});
}

//helpers
var handler_home  = {
	loadMoreFeeds: function(){
		if(!endOfFeed){ //while not end of feed reached
			offset = parseInt(offset) + 10; //load next 10 feeds
			$.ajax({
				 type     : 'POST',
				 url      : '/load_more_feeds',
				 data     : { type:'0', offset : offset },
				 beforeSend: function(){

				 },
				 success: function(html){
					 $(html).hide().appendTo(feed_container).fadeIn(1000);
					 if(!html){ endOfFeed = true; $('<div class="well well-sm bg-white text-center"><b>Nothing to see here<b/></div>').hide().appendTo(feed_container).fadeIn(1000); }
				 },
				 complete: function(data){
					 handler_feeds.init_photo_grid({gutter: 1});
				 },
				 error: function(XMLHttpRequest, textStatus, errorThrown) {
					 alert(errorThrown)
				 }
		 	});
		}
	}
}



//document ready
$(document).ready(init());
