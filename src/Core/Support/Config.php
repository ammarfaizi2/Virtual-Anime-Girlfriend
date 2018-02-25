<?php

namespace Core\Support;

final class Config
{
	private $path;

	private $configFile = [];

	public function __construct($configpath)
	{
		$this->path = $configpath;
	}

	public function get($file, $key = null)
	{
		if (! array_key_exists($file, $this->configFile)) {
			if (! file_exists($file = $this->path."/".$file.".php")) {
				throw new Exception("Config file [{$file}] does not exist");
			}
			$this->configFile[$file] = include $file;
		}
		return $this->getKey($file, $key);
	}

	private function getKey($file, $key)
	{
		if (is_null($key)) {
			return $this->configFile[$file];
		}
		$v = $this->configFile[$file];
		foreach (explode(".", $key) as $val) {
			$v = $v[$val];
		}
		return $v;
	}
}
