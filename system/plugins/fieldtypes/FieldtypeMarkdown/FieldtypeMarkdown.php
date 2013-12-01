
<?php

class FieldtypeMarkdown extends FieldtypeTextarea{


	protected function setup(){
		require_once "Parsedown.php";
	}

	// protected function addStyles(){
	// 	// api('config')->styles->add(api('config')->urls->fieldtypes."{$this->className}/epiceditor/themes/base/epiceditor.css");
	// }
	// protected function addScripts(){
	// 	api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/epiceditor/js/epiceditor.js");
	// 	api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.js");
	// }


	public function outputFormat($value, $format = false){
		$value = Parsedown::instance()->parse($value);
		return $value;
	}


}