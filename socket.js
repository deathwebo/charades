var server = require('http').Server();

var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('charades');

redis.on('message', function(channel, message) {
    message = JSON.parse(message);

    io.emit(channel + ':' + message.event, message);
});

server.listen(3000, function() {
    console.log('Listening on 3000');
});