<?php

namespace Lang\ID\Greetings;

class Greetings
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
			"g1" => 
				[
					"tr" => "*",
					"re" => ["/m(a|e)t.+pa?gi/i"],
					"msg" => [
						"0-11" => [
							["Met pagi juga :short_nickname!", [":l=short_nickname"]],
							["Pagi :short_nickname!", [":l=short_nickname"]],
							["Pagi :short_nickname :v", [":l=short_nickname"]],
							["Pagi, apa kabar :short_nickname", [":l=short_nickname"]]
						],
						"12-15" => [
							["Ini dah siang :short_nickname -_-", [":l=short_nickname"]],
							["Dah siang :short_nickname :v", [":l=short_nickname"]],
						],
						"16-18" => [
							["Dah sore :short_nickname!", [":l=short_nickname"]]
						],
						"19-23" => [
							["Dah malem ini mah"],
							["Udah malem :short_nickname", [":l=short_nickname"]],
							["dah malem :short_nickname", [":l=short_nickname"]]
						]
					]
				],


			"g2" => 
				[
					"tr" => "*",
					"re" => ["/^pa?gi$/"],
					"msg" => [
						"0-11" => [
							["Pagi :short_nickname", [":l=short_nickname"]],
							["Pagi...", []],
							["Pagi juga :short_nickname", [":l=short_nickname"]],
							["Pagi juga", []]
						],
						"12-15" => [
							["Dah siang :short_nickname -_-+", [":l=short_nickname"]],
							["Ini dah siang :short_nickname -__-+!", [":l=short_nickname"]],
							["Dah siang :short_nickname :v", [":l=short_nickname"]]
						],
						"16-18" => [
							["Udah sore njirr :v", []],
							["Dah sore -_-", []],
							["Udah sore :nickname!!", [":=nickname"]],
						],
						"19-23" => [
							["Dah malem :short_nickname", [":l=short_nickname"]],
							["Udah malem :short_nickname", [":l=short_nickname"]],
							["Ini dah malem perasaan :3", [":l=short_nickname"]]
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
			$k = explode("-", $k);
			if (count($k) === 2) {
				if (in_array($now, range($k[0], $k[1]))) {
					return $val[count($val) - 1];
				}
			}
		}
	}
}
