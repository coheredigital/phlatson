<?php

class FieldtypeSelectFieldtype extends FieldtypeSelect{


	protected function setup(){

		$path = $this->api('config')->paths->fieldtypes;
		$paths = glob($path."*", GLOB_ONLYDIR);
		$fieldtypes = array();
		foreach ($paths as $path) {
			$value = basename($path);
			$name = str_replace("Fieldtype", "", $value);
			$fieldtypes["$name"] = "$value";
		}
		$this->selectOptions = $fieldtypes;
	}

}