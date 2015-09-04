/* poll handler */
/* takes care of updating notifications */
//cache
var notif_badge         = $('.notif-badge');
var inbox_badge         = $('.inbox-badge');
var sidebar_inbox_badge = $('.sidebar-inbox-badge');
var sidebar_notif_badge = $('.sidebar-notif-badge');

//variables


//init function
var init = function(){
  //init
  poll_handler.init_notification_sound();
	//workers

	//poll the db for new stuff
  poll_handler.whats_new(10000); //check for new stuff every x seconds

}

//helpers
var poll_handler = {
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
    *  2 -> Inbox Notification response 
    */

    if(response.notifications){
      poll_handler.set_notification_html(notif_badge, response.notifications, false);
      poll_handler.set_notification_html(sidebar_notif_badge, response.notifications, false)
    }
    else if(response.messages){
      poll_handler.set_notification_html(inbox_badge, response.messages, false);
      poll_handler.set_notification_html(sidebar_inbox_badge, response.messages, false);
    }
    else { //else clear it
      poll_handler.set_notification_html(notif_badge, null, true);
      poll_handler.set_notification_html(inbox_badge, null, true);
      poll_handler.set_notification_html(sidebar_inbox_badge, null, true);
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
  }, 
  set_notification_html: function(self, data, clear){
    if(clear == false){
      if(self.text() != data){
        self.text(data);
        ion.sound.play("ping");
      }
    }
    else{
      self.text('');
    }
  }
}



//document ready
$(document).ready(init());
