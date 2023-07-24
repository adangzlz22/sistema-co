// const express  = require('express');

// const app = express();

// const server = require('http').createServer(app);


// const io = require('socket.io')(server,{
//     cors: { origin: "* "}
// });

// io.on('connection',(socket) => {
//     console.log('connection');
//     socket.on('menusChanges',(socket) =>{
//         console.log('menuchanges');
//     });
//     socket.on('disconnect',(socket) =>{
//         console.log('Disconnect');
//     });
// });

// server.listen(3000,() => {
//     console.log('Server is running');
// });

// const express = require("express");
// const path = require("path");
// const app = express();
// const ioClient = require("socket.io-client");
// // const socketio = require("socket.io");
// const Echo = require("laravel-echo");
//
// this.echo = new Echo({
//    broadcaster: 'socket.io',
//    host: 'localhost' + ':' + 6001,
//    client: ioClient,
//    logToConsole: true,
//    transports: ['websocket', 'polling', 'flashsocket'],
// });
// console.log('olis');
//
// this.echo.channel('close-flare').listen('.CloseFlareEvent', data => {
//    console.log(data);
// });
//
// app.use(express.static(path.join(__dirname, "..", "public")));
// module.exports = app;

// var fs = require('fs');
//
// var options = {
//     key: fs.readFileSync('./database/cert/sonora-ssl-apache/sonora.gob.mx.key'),
//     cert: fs.readFileSync('./database/cert/sonora-ssl-apache/9bde25a0d3a1f12.crt')
// };
//
// var httpServer = require('http').createServer();
// var httpsServer = require('https').createServer(options);
// var ioServer = require('socket.io');
//
// var io = new ioServer();
// io.attach(httpServer);
// io.attach(httpsServer);
// httpServer.listen(6003);
// httpsServer.listen(6001);
//
// io.sockets.on('connection', function (socket) {
//     console.log(socket);
// });


var http = require('https');
var server = http.createServer(handler);
var io = require('socket.io')(server);

server.listen(6001);

function handler(req, res) {
    res.writeHead(200);
    res.end('Hello Http');
}

io.on('connection', function(socket) {
    socket.emit('news', {
        hello: 'world'
    });
    console.log('connected!');
});
