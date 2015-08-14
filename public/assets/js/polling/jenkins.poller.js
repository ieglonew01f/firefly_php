/* poll handler */
/* takes care of updating notifications */
//cache
var notif_badge = $('.notif-badge');

//variables


//init function
var init = function(){
  //init
  poll_handler.init_notification_sound();
	//workers

	//poll the db for new stuff
  poll_handler.whats_new(5000); //check for new stuff every x seconds

}

//helpers
var poll_handler  = {
  whats_new : function(frequency){
    //poll the db for new stuff
    setInterval(function(){
      $.getJSON( '/whats_new', function(response){
        poll_handler.handleNewResponse(response);
      });
    }, frequency );
  },
  //handles the response
  handleNewResponse: function(response){
    /* 1 -> Notifications response
    *  2 -> Others // to add later
    */

    if(response.notifications){
      //then do
      //if the data is not same as in dom
      if(notif_badge.text() != response.notifications){
        notif_badge.text(response.notifications);
        ion.sound.play("ping");
      }
    }
    else {
        notif_badge.text('');
    }

  },
  //init notification sound
  init_notification_sound: function(){
    ion.sound({
        sounds: [
            {
                name: "ping"
            }
        ],
        volume: 0.5,
        path: "/public/assets/sound/",
        preload: true
    });
  }
}



//document ready
$(document).ready(init());
