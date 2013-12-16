<?php

class FieldtypeDateTime extends Fieldtype{

	protected function addStyles(){
		api('config')->styles->add($this->url."/datetimepicker/datetimepicker.css");
	}
	protected function addScripts(){
		api('config')->scripts->add($this->url."/datetimepicker/datetimepicker.js");
		api('config')->scripts->add($this->url."/$this->className.js");
	}

	public function outputFormat($value){
		$value = date((string) $this->field->format, (int) $value);
		return $value;
	}

	public function editFormat($value){
		var_dump($this->field);
		$value = (int) $value; // convert value to int
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