<?php

class Field extends DataObject{
	protected $dataFolder = "fields/";
	protected $attributes = null;


    public function type(){
		if ($this->fieldtype) {

			$name = (string) $this->fieldtype;
			$fieldtype = new $name($this);


			return $fieldtype;
		}
		return null;
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