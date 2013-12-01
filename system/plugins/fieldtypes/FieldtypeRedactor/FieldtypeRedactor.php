
<?php

class FieldtypeRedactor extends FieldtypeTextarea{

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."{$this->className}/redactor/redactor.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/redactor/redactor.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.js");
	}

	

	public function format($value, $format = false){
		$value = htmlspecialchars_decode($value);
		return $value;
	}

	// public function saveFormat($value){
	// 	return $value;
	// }

}