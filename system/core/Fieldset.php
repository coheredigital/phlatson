<?php

class Fieldset extends DataObject{
	protected $dataFolder = "fieldgroups/";

	public function fields(){
		$fieldsArray = $this->find("//field");

		$fields = array();
		foreach ($fieldsArray as $f) {
			$field = new Field($f);
			$attr = $f->attributes();

			foreach ($attr as $key => $value) {
				$field->attributes($key, $value);
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