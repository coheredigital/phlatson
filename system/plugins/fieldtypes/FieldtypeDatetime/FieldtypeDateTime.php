<?php

class FieldtypeDateTime extends Fieldtype{

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."$this->className/datetimepicker/datetimepicker.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/datetimepicker/datetimepicker.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."$this->className/$this->className.js");
	}

	public function outputFormat($value){
		$value = date((string) $this->field->format, (int) $value);
		return $value;
	}

	public function editFormat($value){
		var_dump($value);
		var_dump($this->field->format);
		$value = date($this->field->format, $value);
		return $value;
	}

	public function saveFormat($name, $value){

		$dom = new DomDocument;
        $node = $dom->createElement("$name", (int) strtotime($value));
        $dom->appendChild($node);

        return $dom->documentElement;
	}



}