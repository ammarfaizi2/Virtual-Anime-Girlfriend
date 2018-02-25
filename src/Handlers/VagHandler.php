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
