<?php

class Field extends DataObject{
	protected $dataFolder = "fields/";
	protected $attributes = array();

	protected function setBasePath(){
		return $this->api('config')->paths->fields;
	}

	public function type(){
		// var_dump($this->data->saveHTML());
		// print($this->data->saveHTML());

		if ($this->fieldtype) {
			$name = $this->fieldtype;
			$fieldtype = new $name;
		}
		return $fieldtype;
	}

	public function attributes($key, $value = null){
		if (isset($value)) {
			$this->attributes["$key"] = $value;
		}
		else return $this->attributes["$key"];
	}

/*

this needs a better method

 */
	public function getTemplate(){
		$template = new Template("field");
		return $template;
	}

}