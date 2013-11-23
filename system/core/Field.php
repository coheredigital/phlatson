<?php

class Field extends DataObject{
	private $attributes = array();

	protected function setBasePath(){
		return $this->api('config')->paths->fields;
	}

	public function type(){
		if ($this->data->fieldtype) {
			$name = (string) $this->data->fieldtype;
			$fieldtype = new $name();
		}
		return $fieldtype;
	}

	public function attributes($key, $value = null){
		if (isset($value)) {
			$this->attributes["$key"] = $value;
		}
		else return $this->attributes["$key"];
	}

}