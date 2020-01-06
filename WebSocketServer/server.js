/*jshint esversion: 6 */

var app = require('http').createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base) 

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor, 
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require('socket.io')(app);

var LoggedUsers = require('./loggedusers.js');

app.listen(8080, function(){
    console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users. 
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

let loggedUsers = new LoggedUsers();

io.on('connection', function (socket) {
    console.log('client has connected (socket ID = '+socket.id+')' );

    socket.on('user_enter', function(user) {
        if (user) {
          loggedUsers.addUserInfo(user, socket.id);
          console.log(user.name);
          console.log(socket.id);
        }
      });
      socket.on('user_exit', function(user) {
        if (user) {
          loggedUsers.addUserInfo(user, socket.id);
          console.log("User has disconnected (socket ID = " + socket.id + ")");
        }
      });
    
      socket.on('privateMessage', function(msg, sourceUser, destUser) {
        let userInfo = loggedUsers.userInfoByID(destUser.id);
        let socket_id = userInfo !== undefined ? userInfo.socketID : null;
        if (socket_id === null) {
          socket.emit("privateMessage_unavailable", destUser);
        } else {
          io.to(socket_id).emit("privateMessage", msg, sourceUser);
          socket.emit("privateMessage_sent", msg, destUser);
        }
      });
    
      socket.on('disconnect', function() {
        console.log(
          "User has disconnected (socket ID = " + socket.id + ")"
        );
      });

    // socket.on('user_enter',(user) =>{
    //     loggedUsers.addUserInfo(user, socket.id);
    //     socket.join('logged');
    //     console.log(user.email+" entered")
    // });

    // socket.on('user_exit',(user) =>{
    //     if(user){
    //         console.log('client has disconnected ('+user.email+') (socket ID = '+socket.id+')');
    //         socket.leave('logged');
    //     }
    // });
    
    socket.on('income_movement',(destinationID, destinationEmail, sourceID) =>{
        console.log(destinationID);
        if(destinationID){
            console.log('Income movement made on wallet '+destinationID+'(socket ID = '+socket.id+')');
            const userInfo = loggedUsers.userInfoByID(destinationID);
            if(userInfo){
                io.to(userInfo.socketID).emit('income_movement_made');
                io.to(userInfo.socketID).emit('update_wallet');
                io.to(userInfo.socketID).emit('update_movements');
            }else{
                //enviar mail
                const source = loggedUsers.userInfoByID(sourceID);
                if(source){
                    io.to(source.socketID).emit('notify_by_email', destinationEmail);
                }
                console.log("send email to "+destinationEmail);
            }
        }
    });

    // socket.on('transfer_movement',(destinationID, destinationEmail, sourceID) =>{
    //     if(destinationID){
    //         console.log('Transfer movement to wallet '+destinationID+' (socket ID = '+socket.id+')');
    //         const userInfo = loggedUsers.userInfoByID(destinationID);
    //         if(userInfo){
    //             io.to(userInfo.socketID).emit('transfer_movement_made');
    //             io.to(userInfo.socketID).emit('update_wallet');
    //             io.to(userInfo.socketID).emit('update_movements');
    //         }else{
    //             //enviar mail
    //             const source = loggedUsers.userInfoByID(sourceID);
    //             if(source){
    //                 io.to(source.socketID).emit('notify_by_email', destinationEmail);
    //             }
    //             console.log("send email to "+destinationEmail);
    //         }
    //     }
    // });
});
