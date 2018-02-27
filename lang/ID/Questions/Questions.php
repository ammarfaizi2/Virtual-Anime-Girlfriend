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
		$indoDay = [
			"Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"
		];

		$indoMonth = [
			"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
		];

		$this->responseMessages = [
			"q1" => 
				[
					"tr" => "*",
					"re" => ["/ja?m\s{1,3}(be?ra?(pa?)?)/i", "/se?ka?ra?n?g\s{1,3}ja?m\s{1,3}be?ra?(pa?)?/i"],
					"msg" => [
						"*" => [
							["Sekarang jam ".date("h:i:s A")." :short_nickname", [":l=short_nickname"]],
							["Jam ".date("h:i:s A")]
						]
					]
				],

			"q2" => 
				[
					"tr" => "*",
					"re" => ["/a?pa\s{1,3}ka?ba?r/i", "/g?ima?na?\s{1,3}ka?ba?r(nya)?/i"],
					"msg" => [
						"*" => [
							["Baik :short_nickname, kamu gimana?", [":l=short_nickname"]],
							["Sehat :short_nickname, kamu apa kabar?", [":l=short_nickname"]]
						]
					]
				],

			"q3" =>
				[
					"tr" => "*",
					"re" => ["/ha?ri?\s{1,3}apa?\s{1,3}se?ka?ra?ng/i"],
					"msg" => [
						"*" => [
							["Sekarang hari ".$indoDay[date("n")]]
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
					return $val[rand(0, count($val) - 1)];
				}
			} elseif ($k[0] === "*") {
				if (in_array($now, range($k[0], $k[1]))) {
					return $val[rand(0, count($val) - 1)];
				}
			}
		}
	}
}
