<?php

class FieldtypeDateTime extends Fieldtype{

	public function __construct(){
		$this->className = get_class($this);
		$config = api("config");
		$config->scripts->add($config->urls->fieldtypes."$this->className/pickadate/lib/compressed/picker.js");
		$config->scripts->add($config->urls->fieldtypes."$this->className/pickadate/lib/compressed/picker.date.js");
		$config->scripts->add($config->urls->fieldtypes."$this->className/$this->className.js");

		$config->styles->add($config->urls->fieldtypes."$this->className/pickadate/lib/themes/default.css");
		$config->styles->add($config->urls->fieldtypes."$this->className/pickadate/lib/themes/default.date.css");
	}

	public function format($value, $format){
		$value = date((string) $format, (int) $value);
		return $value;
	}

}