<?php

class Fieldsets extends ObjectArray{

	public function __construct(){
		$this->dataFolder = "/fieldgroups/";
		$this->singularName = "Fieldgroup"; // we can strtoilower() this for some needs, naming MUST be consistent for this
		$this->load();
	}

	public function fields(){
		$fieldsArray = $this->find("//field");

		$fields = array();
		foreach ($fieldsArray as $f) {
			$field = new Field($f->nodeValue);
			$attr = $f->attributes;
			foreach ($attr as $a) {
				$field->attributes($a->nodeName, $a->nodeValue);
			}

			$fields["$field->name"] = $field;

		}
		
		return $fields;
	}


	public function get($name){
		switch ($name) {
			case 'fields':
				return $this->fields();
				break;
			default:
				return parent::get($name);
				break;
		}
	}


}