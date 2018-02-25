<?php

namespace Core\Foundation;

use Core\Hub\Singleton;
use Core\Support\Config;

class Application
{
	public function __construct($build)
	{
		$this->singleton = Singleton::getInstance();
		$this->singleton->register("config", new Config($build["config_path"]));
		$this->loadHelper();
	}

	public function register()
	{
	}

	private function loadHelper()
	{
		require __DIR__."/../../helpers.php";
	}
}
