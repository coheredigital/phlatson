<?php

namespace Phlatson;

require_once __DIR__ . DIRECTORY_SEPARATOR . "Parsedown.php";

class FieldtypeMarkdown extends Fieldtype
{

	public function decode($value)
	{

		$parsedown = new \Parsedown();

		$value = $parsedown->text($value);

		return $value;
	}
}
