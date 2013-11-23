<?php

class FieldtypeDateTime extends Fieldtype{

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/themes/default.css");
		api('config')->styles->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/themes/default.date.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/compressed/picker.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/pickadate/lib/compressed/picker.date.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/$this->className.js");
	}

	public function outputFormat($value, $format){
		$value = date((string) $format, (int) $value);
		return $value;
	}

	public function saveFormat($value){
		return (int) strtotime($value);
	}

	public function render(){

		$out = new \markup\Fielditem;
		$out->attributes = $this->getAttributes();
		$out->name = (string) $this->name;
		$out->label = (string) $this->label;
		$out->value = $this->value;
		$attributes = $this->getAttributes();
		return $output = $out->render();
	}


}