<?php

namespace Core\Foundation;

use Core\Support\Config;

abstract class MessageAbstraction
{
	/**
	 * @var string
	 */
	protected $input;

	/**
	 * @var string
	 */
	protected $config;

	/**
	 * @var mixed
	 */
	protected $result = [];

	/**
	 * Constructor.
	 *
	 * @param \Core\Support\Config
	 * @return void
	 */
	final public function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * @param string $string
	 * @return void
	 */
	final public function setInput($input)
	{
		$this->input = $input;
		$this->handle();
	}

	abstract protected function handle();

	abstract public function isMatch();

	public function beforeGet()
	{
	}

	/**
	 * @return mixed
	 */
	final public function get()
	{
		$this->beforeGet();
		return $this->bind($this->result);
	}

	/**
	 * @param string $bind
	 */
	private function bind($bind)
	{
		if (is_array($bind)) {
			if (isset($bind[1])) {
				$r = [];
				foreach ($bind[1] as $k => $val) {
					preg_match("/:(.*)=(.*)/", $val, $val);
					$bind[1][$k] = ":".$val[2];
					$r = config("my_profile.".$val[2]);
					if ($val[1] !== "") {
						foreach (str_split($val[1]) as $l) {
							switch ($l) {
								case 'l':
									$r = strtolower($r);
									break;
								
								default:
									throw new Exception("Invalid parameter {$l}", 1);
									break;
							}
						}
					}
				}
				$bind[0] = str_replace($bind[1], $r, $bind[0]);
			}
			return $bind[0];
		}
		return $bind;
	}
}