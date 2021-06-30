const app = require('express')()
const socket = require('socket.io')
let server = null
let io = null

app.all('/init', (req, res) => {
  if (!server) {
    server = res.socket.server
    io = socket(server, {
      transports: ['polling'],
      serveClient: false,
      pingTimeout: 2500,
      pingInterval: 5000,  
      cookie: false
    })

    io.on('connection', function (socket) {
      console.log('user connection')
      socket.emit('connected-callback', true) 

      socket.on('update-global', () => {
        io.sockets.emit('global-score')
      })
      socket.on('disconnect', () => console.log('disconnected'))
    })
  }
  res.json({ msg: 'server is set' })
})
module.exports = app
