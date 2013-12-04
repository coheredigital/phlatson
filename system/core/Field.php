<?php

class Field extends DataObject{
	protected $dataFolder = "fields/";
	protected $attributes = array();


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

	public function get($name){
		switch ($name) {
			case 'type':
				return $this->type();
				break;
			case 'template':
				$template = new Template("field");
				return $template;
				break;
			default:
				return parent::get($name);
				break;
		}
	}


}