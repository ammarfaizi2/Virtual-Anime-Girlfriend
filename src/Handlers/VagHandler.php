<?php

namespace Handlers;

define("BASEPATH", realpath(__DIR__."/../.."));

use Core\Vag;
use Core\Foundation\Application;

final class VagHandler
{
	private $app;

	public function __construct()
	{
		$this->app = new Application(
			[
				"config_path" => BASEPATH."/config"
			]
		);
	}

	private function shell_exec($cmd)
	{
		while (@ ob_end_flush());
		$proc = popen($cmd, 'r');
		echo "\n";
		while (!feof($proc)) {
		    echo fread($proc, 4096);
		    @ flush();
		}
		echo "\n";
	}

	public function run()
	{
		if (isset($_SERVER["argv"][1])) {
			$fb = config("bot_profile.bot_facebook");
			switch ($_SERVER["argv"][1]) {
				case 'listen':
						if (array_search("--no-login", $_SERVER["argv"]) !== false) {
							echo $cmd = "js ./vag.js login \"{$fb['email']}\" \"{$fb['password']}\"\n";
							echo self::shell_exec($cmd);
						}
						echo $cmd = "js ./vag.js listen \"".rawurlencode(json_encode([
							"listen_to" => $fb["listen_to"],
							"bot_user_id" => $fb["user_id"]
						]))."\"\n";
						echo self::shell_exec($cmd);
					break;
				case 'facebook':
					$data = $_SERVER["argv"][2];
					$data = json_decode(urldecode($data), true);
					if ($data["senderID"] != $fb["user_id"]) {
						if (isset($data["body"]) && is_string($data["body"])) {
							$start = microtime(true);
							if (strtolower(substr($data["body"], 0, 3)) == "sh ") {
								$data["body"] = substr($data["body"], 3);
								file_put_contents("/tmp/vag_exec.sh", "#!/bin/sh\n{$data["body"]}");
								shell_exec("sudo chmod 777 /tmp/vag_exec.sh");
								$resp = shell_exec("/tmp/vag_exec.sh 2>&1");
							} else {
								$this->vag = new Vag($this->app);
								$this->vag->setInput($data["body"]);
								$this->vag->exec();
								$resp = json_encode($this->vag->getResponse());
							}
							echo json_encode(["send" => true, "response" => $resp."\n\n".("Exection time: ".(microtime(true) - $start)."\n\n")]);
						} else {
							echo json_encode(["send" => false]);
						}
					}
					break;
				default:
					break;
			}
			
		} else {
			$botname = config("bot_profile.profile.first_name");
			while (true) {
				echo "You: ";
				$input = trim(fgets(STDIN, 1024));
				$start = microtime(true);
				$this->systemCmd($input);
				$this->vag = new Vag($this->app);
				$this->vag->setInput($input);
				$this->vag->exec();
				echo $botname.": ".json_encode($this->vag->getResponse())."\n";
				echo "Exection time: ".(microtime(true) - $start)."\n\n";
			}
		}
	}

	private function systemCmd($input)
	{
		$input = strtolower($input);
		switch ($input) {
			case 'exit':
				echo "\n\nExit.\n";
				exit(0);
				break;
			
			default:
				break;
		}
	}
}
