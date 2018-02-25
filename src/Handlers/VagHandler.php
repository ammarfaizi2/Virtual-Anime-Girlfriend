<?php

namespace Handlers;

define("BASEPATH", realpath(__DIR__."/../.."));

use Core\Foundation\Application;

final class VagHandler
{
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

	}
}
