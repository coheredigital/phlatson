<?php

class FieldtypeText extends Fieldtype{



	public function getInput(){

		$input = api("extensions")->get("InputtypeText");
		return $input;

	}


}