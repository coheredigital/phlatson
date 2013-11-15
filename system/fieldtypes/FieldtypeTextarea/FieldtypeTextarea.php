
<?php

class FieldtypeTextarea extends Fieldtype{
	
	public $className;

	public function __construct(){
		$this->className = get_class($this);
		$config = api("config");
		$config->scripts->add($config->urls->fieldtypes."{$this->className}/redactor/redactor.js");
		$config->scripts->add($config->urls->fieldtypes."{$this->className}/{$this->className}.js");
		$config->styles->add($config->urls->fieldtypes."{$this->className}/redactor/redactor.css");
	}

	public function render($name, $value){
		return "<textarea class='field-input {$this->className}' name='$name' id='Input_$name' cols='30' rows='10'>{$value}</textarea>";
	}

}