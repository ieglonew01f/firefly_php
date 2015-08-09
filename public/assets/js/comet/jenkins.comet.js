/* Jenkins comet chat client side api */
var comet = (function($){
  //Session username
  var jenkins_session_data  = $('#jenkins_session_data');
  var session_username      = jenkins_session_data.data('username');

  /* SERVER ADDRESS */
  var socket = io.connect('http://localhost:3000');

  //register user on the socket network
  var register = function(data){
    socket.emit('register', data);
  }

  $.arrayIntersect = function(a, b)
  {
      return $.grep(a, function(i)
      {
          return $.inArray(i, b) > -1;
      });
  };

  //refresh chat list
  //who is online
  var chat_list = function(friend_list, callback){
    socket.emit('who is online', data);
    socket.on('update chat list', function (data) {
      callback($.arrayIntersect(data, friend_list)); //throws online friends list
    });
  }

  //send message handler
  var send_message = function(data, callback){
    socket.emit('send message', data);
    callback();
  }

  //recieve message handler
  var new_message = function(callback){
    socket.on('new message', function (data) {
      callback(data);
    });
  }

  socket.on('test', function (data) {
    console.log(data)
  });

  return {
      register     : register,
      chat_list    : chat_list,
      send_message : send_message,
      new_message  : new_message
  };

})(jQuery);
