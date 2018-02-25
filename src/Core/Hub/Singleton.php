<?php

namespace Core\Hub;

final class Singleton
{
	private static $instance;

	private $instances = [];

	public function register($name, $instance)
	{
		$this->instances[$name] = $instance;
	}

	public static function get($name)
	{
		return self::getInstance()->instances[$name];
	}

	public static function getInstance()
	{
		if (! isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function gi()
	{
		return self::getInstance();
	}
}
