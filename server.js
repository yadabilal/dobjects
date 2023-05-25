var app = require('express')();
var http = require('http').Server(app);
var socket = require('socket.io')(http);


app.get('/', function (req, res) {
  res.send("burası anaysafa");
});




app.listen(3000, function () {
  console.log('serve çalışıyıo');
});