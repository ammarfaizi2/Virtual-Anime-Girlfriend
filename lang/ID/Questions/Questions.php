<?php

namespace Lang\ID\Questions;

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
					"re" => ["/ja?m\s(be?ra?(pa?)?)/i", "/se?ka?ra?n?g\sja?m\sbe?ra?(pa?)?/"],
					"msg" => [
						"*" => [
							["Sekarang jam ".date("h:i:s A")." :short_nickname", [":l=short_nickname"]],
							["Jam ".date("h:i:s A")]
						]
					]
				]
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
			if ($k == "*") {
				return $val[rand(0, count($val) - 1)];
			}
			$k = explode("-", $k);
			if (count($k) === 2) {
				if (in_array($now, range($k[0], $k[1]))) {
					return $val[rand(0, count($val) - 1)];
				}
			}
		}
	}
}
