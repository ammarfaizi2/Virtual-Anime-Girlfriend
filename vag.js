#!/usr/bin/env node
/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license GPL
 */

"use strict";

var argv = process.argv;

if (typeof argv[2] != "undefined") {
	switch (argv[2]) {
		case "login":
			if (typeof argv[3] == "undefined" || typeof argv[4] == "undefined") {
				console.log("Please provide email and password!");
				return;
			}
			var st = require("./vag_handler/login");
				st.login(argv[3], argv[4]);
		break;

		case "listen":
			var st = require("./vag_handler/listen");
				argv = argv.slice(3);
				st.listen(decodeURIComponent(argv[0]));
		break;
	}
}
