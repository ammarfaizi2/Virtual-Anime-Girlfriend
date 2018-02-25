<?php

use Core\Hub\Singleton;

if (! function_exists("config")) {
	function config($cfg)
	{
		return Singleton::get("config")->get(...explode(".", $cfg, 2));
	}
}
