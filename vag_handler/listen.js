
"use strict";

const fs = require("fs");
const login = require("facebook-chat-api");
const exec = require('child_process').exec;

module.exports = {
    "listen": function (fb) {
        fb = JSON.parse(fb);
        login({appState: JSON.parse(fs.readFileSync('storage/state/appstate.json', 'utf8'))}, (err, api) => {
            if(err) return console.error(err);
            console.log(fb);
            // api.setOptions({
            //     selfListen: true,
            //     logLevel: "silent"
            // });

            api.listen((err, message) => {
                if(err) return console.error(err);
                if (fb["listen_to"].indexOf(message.threadID) != -1 && message.senderID != fb["bot_user_id"]) {
                    var child = exec('php vag facebook "'+encodeURIComponent(JSON.stringify(message))+'"',
                        function (error, stdout, stderr) {
                            var s = JSON.parse(decodeURIComponent(stdout));
                            if (s["send"] == true) {
                                api.sendMessage(s["response"], message.threadID);
                            }
                    });
                }
            });
        });
    }
};
