<?php

namespace App\Messages;

use Core\Contracts\Messages as MessagesContract;

class Greetings implements MessagesContract
{
	private $messages;

	public function __construct()
	{

	}
}
