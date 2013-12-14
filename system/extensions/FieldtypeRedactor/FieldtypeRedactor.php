
<?php

class FieldtypeRedactor extends FieldtypeTextarea{

	protected function addStyles(){
		api('config')->styles->add($this->url."redactor/redactor.css");
	}
	protected function addScripts(){
		api('config')->scripts->add($this->url."redactor/redactor.js");
		api('config')->scripts->add($this->url."{$this->className}.js");
	}

}