<?php

class FieldtypeDateTime extends Fieldtype{

	const SCRIPT = "pickadate/lib/compressed/picker.js";

	public static function getInfo() {
		return array(
			'title' => 'Datetime Field', 
			'version' => 101, 
			'summary' => 'An example module used for demonstration purposes. See the /site/modules/Helloworld.module file for details.',
			'href' => 'http://amsul.ca/pickadate.js/',
		);
	}

	public function format(){
		$this->value = date((string) $this->format, (int) $this->value);
	}

}