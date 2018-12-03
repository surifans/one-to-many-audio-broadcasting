<!DOCTYPE html>
<html lang="en">
    <head>
        

        
        
        <script src="https://cdn.webrtc-experiment.com/socket.io.js"></script>
        <script src="meeting_student.js"> </script>
    </head>
    <body>
        <div id="local-media-stream"></div>
        <div id="remote-media-streams"></div>
       
		
        <script>
            var hash = 66;
            var meeting = new Meeting(hash);
			//meeting.setup('meeting room name');
			
            var remoteMediaStreams = document.getElementById('remote-media-streams');
            var localMediaStream = document.getElementById('local-media-stream');

            var channel = 66;
                var sender = Math.round(Math.random() * 999999999) + 999999999;

                var SIGNALING_SERVER = 'https://socketio-over-nodejs2.herokuapp.com:443/';
                io.connect(SIGNALING_SERVER).emit('new-channel', {
                    channel: channel,
                    sender: sender
                });

                var socket = io.connect(SIGNALING_SERVER + channel);
                socket.on('connect', function () {
                    // setup peer connection & pass socket object over the constructor!
                });

                socket.send = function (message) {
                    socket.emit('message', {
                        sender: sender,
                        data: message
                    });
                };

                meeting.openSignalingChannel = function(callback) {
                    return socket.on('message', callback);
                };

        // on getting media stream
            meeting.onaddstream = function(e) {
                if (e.type == 'local') localMediaStream.appendChild(e.audio);
                if (e.type == 'remote') remoteMediaStreams.insertBefore(e.audio, remoteMediaStreams.firstChild);
            };

        // using firebase for signaling
            meeting.firebase = 'rtcweb';

        // if someone leaves; just remove his audio
            meeting.onuserleft = function(userid) {
                var audio = document.getElementById(userid);
                if (audio) audio.parentNode.removeChild(audio);
            };

        // check pre-created meeting rooms
            meeting.check();

            
        </script>
        
    </body>
</html>