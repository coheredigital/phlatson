
<?php

class FieldtypeMarkdown extends FieldtypeTextarea{


	protected function setup(){
		require_once "Parsedown.php";
	}

	// protected function addStyles(){
	// 	api('config')->styles->add($this->url."epiceditor/themes/base/epiceditor.css");
	// }
	// protected function addScripts(){
	// 	api('config')->scripts->add($this->url."epiceditor/js/epiceditor.js");
	// 	api('config')->scripts->add($this->url."{$this->className}.js");
	// }


	public function getOutput($value){
		return Parsedown::instance()->parse($value);
	}

	public function getEdit($value){
		return trim($value);
	}


}