<?php

namespace App\Messages;

use Core\Foundation\MessageAbstraction;

class Greetings extends MessageAbstraction
{
	private $ins;

	public function handle()
	{
		$lang = config("bot_profile.lang_1");
		$ins = "\\Lang\\{$lang}\\Greetings\\Greetings";
		$this->ins = new $ins;
		$this->ins->setInput($this->input);
	}

	public function isMatch()
	{
		return $this->ins->isMatch();
	}

	public function beforeGet()
	{
		$this->result = $this->ins->getResponse();
	}
}
