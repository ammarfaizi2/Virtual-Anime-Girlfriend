<?php

namespace Lang\EN\Questions;

class Questions
{
	private $input;

	private $messages = [];

	private $responseMessages = [];

	public function __construct()
	{
	}

	public function buildResponse()
	{

		$this->responseMessages = [
			"q1" => 
				[
					"tr" => "*",
					"re" => ["/^((wh)a?t)\s{1,3}time\s?(is\s(it)?\??)?$/i"],
					"msg" => [
						"*" => [
							[date("h:i:s A"), []]
						]
					]
				],

			"q2" => 
				[
					"tr" => "*",
					"re" => ["/^((wh)a?t)\s{1,3}day\s?(is\s?(it)?\s?(to?m?m?or?ow?)\??)?$/i"],
					"msg" => [
						"*" => [
							["Tommorow is ".date("l", time()+3600*24), []]
						]
					]
				],

			"q2" => 
				[
					"tr" => "*",
					"re" => ["/^((wh)a?t)\s{1,3}day\s?(is\s?(it)?\s?(ye?s?te?rday?)\??)?$/i"],
					"msg" => [
						"*" => [
							["Yesterday is ".date("l", time()-3600*24), []]
						]
					]
				],

			"q3" => 
				[
					"tr" => "*",
					"re" => ["/^((wh)a?t)\s{1,3}day\s?(is\s?(it)?\s?(today)?\??)?$/i"],
					"msg" => [
						"*" => [
							["Today is ".date("l"), []]
						]
					]
				],
		];
	}

	public function setInput($input)
	{
		$this->input = $input;
		$this->buildResponse();
	}

	public function isMatch()
	{
		foreach ($this->responseMessages as $key => $val) {
			if ($this->checkTimerange($val["tr"])) {
				if ($this->match($val["re"])) {
					$this->responseMessages = $this->selectResponse($val["msg"]);
					return true;
				}
			}
		}
		return false;
	}

	private function checkTimerange($time)
	{
		if ($time === "*") {
			return true;
		}
	}

	public function getResponse()
	{
		return $this->responseMessages;
	}

	private function match($regex)
	{
		if (is_array($regex)) {
			foreach ($regex as $sregex) {
				$found = 1;
				if (is_array($sregex)) {
					foreach ($sregex as $ssregex) {
						$found &= preg_match($ssregex, $this->input);
					}
				} else {
					$found &= preg_match($sregex, $this->input);
				}

				if ($found) {
					return true;
				}
			}
			return false;
		}
		return (bool) preg_match($regex, $this->input);
	}

	private function selectResponse($resp)
	{
		$now = (int)date("H");
		foreach ($resp as $k => $val) {
			$k = explode("-", $k);
			if (count($k) === 2) {
				if (in_array($now, range($k[0], $k[1]))) {
					return $val[rand(0, count($val) - 1)];
				}
			} elseif ($k[0] === "*") {
				return $val[rand(0, count($val) - 1)];
			}
		}
	}
}
