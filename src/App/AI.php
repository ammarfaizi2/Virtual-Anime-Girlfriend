<?php

namespace App;

use App\Messages\Greetings;
use Core\Foundation\Application;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 */
class AI
{
	/**
	 * @var \Core\Vag
	 */
	private $vag;

	/**
	 * @var \Core\Foundation\Application
	 */
	private $app;

	/**
	 * @var \App\Messages\Greetings
	 */
	private $greetings;

	/**
	 * @var array
	 */
	private $result = [];

	/**
	 * Constructor.
	 *
	 * @param \Core\Foundation\Application
	 * @return void
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->vag = $this->app->singleton->get("vag");
	}

	/**
	 * @return voi
	 */
	public function exec()
	{
		$this->greetings = new Greetings();
		$this->greetings->setInput($this->vag->input);

		if ($this->greetings->isMatch()) {
			$this->result = $this->greetings->get();
			return;
		}
	}

	/**
	 * @return mixed
	 */
	public function get()
	{
		return $this->result;
	}
}
