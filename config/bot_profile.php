<?php

return [
	"bot_facebook" => [
		"email" => "",
		"password" => "",
		"connection" => "keep-alive"
	],

	"bot_telegram" => [
		"username" => "",
		"token" => ""
	],

	"bot_line" => [
		"channel_secret" => "",
		"channel_token" => ""
	],

	"live_notification" => [
		"host" => "130.211.227.164",
		"port" => "8331",
		"android_id" => "",
		"do_not_distrub" => [
			
			/**
			 * Format [0-12]:[0-12]\s?(AM|PM)\s-\s[0-12]:[0:12]\s?(AM|PM)
			 *
			 * Example -> 07:00 AM - 10:00 AM
			 *
			 */
			"07:00 AM - 10:00 AM" // working time
		]

		/**
		 * 1 = on
		 * 0 = off
		 */
		"stream" => 1
	]
];
