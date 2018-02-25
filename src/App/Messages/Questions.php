<?php

namespace App\Messages;

use Core\Foundation\MessageAbstraction;

class Questions extends MessageAbstraction
{
	private $ins;

	public function handle()
	{
		$lang = config("bot_profile.lang_1");
		$ins = "\\Lang\\{$lang}\\Questions\\Questions";
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
