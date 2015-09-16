var express = require('express'),
	app = express(),
	server = require('http').createServer(app),
	io = require('socket.io').listen(server);

	server.listen(3000);
	app.use(express.static(__dirname + '/public'));
	app.get('/', function(req, res){
		res.sendfile(__dirname + '/index.html');
	});

  var connectedUsers = [];

io.sockets.on('connection', function(socket){
	//register user on to the server
	socket.on('register', function(data){
		socket.nickname = data.username;
		connectedUsers[socket.nickname] = socket;
	});

	//who is online
	socket.on('who is online', function(data){
		io.sockets.emit('update chat list', Object.keys(connectedUsers));
	});

	//on new message
	socket.on('send message', function(data){
		if(connectedUsers[data.for_username]){
			connectedUsers[data.for_username].emit('new message', data);
		}
	});

	//on is typing
	socket.on('is typing', function(data){
		if(connectedUsers[data.for_username]){
			connectedUsers[data.for_username].emit('set typing', data);
		}
	});

	//unset is typing
	socket.on('is not typing', function(data){
		if(connectedUsers[data.for_username]){
			connectedUsers[data.for_username].emit('set not typing', data);
		}
	});


	//not working to do later
	//some one died X_X socket disconnect
	socket.on('disconnect', function () {
		if(!socket.nickname) return;
		io.sockets.emit('someone died', socket.nickname);
		delete connectedUsers[socket.nickname]
		//io.sockets.emit('update chat list', Object.keys(connectedUsers));
	});

});
