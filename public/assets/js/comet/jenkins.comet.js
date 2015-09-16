/* Jenkins comet chat client side api */
var comet = (function($){
  //Session username
  var jenkins_session_data  = $('#jenkins_session_data');
  var session_username      = jenkins_session_data.data('username');

  /* SERVER ADDRESS */
  var socket = io.connect('http://128.199.192.79:3000');
  //var socket = io.connect('192.168.0.7:3000');

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

  //throw is typing
  var is_typing = function(data, callback){
    socket.emit('is typing', data);
    callback();
  }

  //throw is not typing
  var no_is_typing = function(data, callback){
    socket.emit('is not typing', data);
    callback();
  }

  //get / set is typing
  var set_typing = function(callback){
    socket.on('set typing', function (data) {
      callback(data); 
    });
  }

  //set not typing
  var set_not_typing = function(callback){
    socket.on('set not typing', function (data) {
      callback(data); 
    });
  }

  //someone died/disconnected
  var someone_died = function(callback){
    socket.on('someone died', function(data){
      callback(data);
    });
  };

  return {
      register       : register,
      chat_list      : chat_list,
      send_message   : send_message,
      new_message    : new_message,
      is_typing      : is_typing,
      set_typing     : set_typing,
      set_not_typing : set_not_typing,
      no_is_typing   : no_is_typing,
      someone_died   : someone_died
  };

})(jQuery);
