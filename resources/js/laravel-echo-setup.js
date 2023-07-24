// import Echo from "laravel-echo"
// window.io = require('socket.io-client');
//
// // window.Echo = new Echo({
// //     broadcaster: 'socket.io',
// //     host: window.location.hostname + ':6001',
// //     path: '/socket.io'
// // });
//
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: { path: '/socket.io' }
// });

import Echo from "laravel-echo";
window.io = require('socket.io-client');
// Have this in case you stop running your laravel echo server
if (typeof io !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: { path: '/socket.io' }
    });
    console.log('socket.io-client on the path /socket.io.');
}else{
    console.log('socket.io-client is undefined...');
}


