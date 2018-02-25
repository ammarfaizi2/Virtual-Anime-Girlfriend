
"use strict";

const fs = require("fs");
const login = require("facebook-chat-api");
const exec = require('child_process').exec;

module.exports = {
    "listen": function (to) {
        to = JSON.parse(to);
        login({appState: JSON.parse(fs.readFileSync('storage/state/appstate.json', 'utf8'))}, (err, api) => {
            if(err) return console.error(err);
            console.log(to);
            api.setOptions({
                selfListen: true,
                logLevel: "silent"
            });

            api.listen((err, message) => {
                if(err) return console.error(err);
                if (to.indexOf(message.threadID) != -1) {
                    var child = exec('php vag facebook "'+encodeURIComponent(JSON.stringify(message))+'"',
                        function (error, stdout, stderr) {
                            console.log(stdout);
                            api.sendMessage(stdout, message.threadID);
                    });
                }
            });
        });
    }
};
