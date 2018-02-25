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

	public function run()
	{
		if (isset($_SERVER["argv"][1])) {
			switch ($_SERVER["argv"][1]) {
				case 'listen':
						$fb = config("bot_profile.bot_facebook");
						if (! array_search("--no-login", $_SERVER["argv"])) {
							echo shell_exec("js ./vag.js login \"{$fb['email']}\" \"{$fb['password']}\"");
						}
						echo shell_exec("js ./vag.js listen \"".urlencode(json_encode([
							"listen_to" => $fb["listen_to"],
							"bot_user_id" => $fb["user_id"]
						]))."\"");
					break;
				case 'facebook':
					$data = $_SERVER["argv"][2];
					var_dump($data);
					$data = json_decode(urldecode($data), true);
					// var_dump($data);
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
