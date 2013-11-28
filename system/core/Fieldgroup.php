<?php

class Fieldgroup extends DataObject{
	protected $dataFolder = "fieldgroups/";

	protected function setBasePath(){
		return api('config')->paths->templates;
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