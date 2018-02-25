<?php

namespace Core;

use App\AI;
use Core\Foundation\Application;

final class Vag
{
	/**
	 * @var \Core\Foundation\Application
	 */
	private $app;

	/**
	 * @var string
	 */
	private $sentence;

	/**
	 * @var array
	 */
	private $response = [];

	/**
	 * @var string
	 */
	public $input;

	/**
	 * Constructor.
	 *
	 * @param \Core\Foundation\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->app->singleton->register("vag", $this);
		$this->ai  = new AI($app);
	}

	/**
	 * @param string $input
	 * @return void
	 */
	public function setInput($input)
	{
		$this->input = $input;
	}

	/**
	 * @return bool
	 */
	public function exec()
	{
		$this->ai->exec();
		$this->response = $this->ai->get();
	}

	/**
	 * @return array
	 */
	public function getResponse()
	{
		return $this->response;
	}
}
