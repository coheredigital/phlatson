<?php
/*

Used in the management of Admin Pages and layouts, uses some special feature and overrides that extend from the base Page class

-- override layout discovery methods
-- doesn't require exisiting templates since Admin pages are render using alternative methods

 */

class AdminPage extends Page{

	// define some protected variable to be used by all page objects

	function __construct($url = false){
		parent::__construct($url);
		array_shift($this->urlRequest);


		$this->path = trim($this->basePath.$this->directory, "/")."/";
		$this->data = $this->getXML();
	}

	protected function setBasePath(){
		return $this->api('config')->paths->admin."content/";
	}

	public function url(){
		return 	$this->api('config')->urls->root.$this->api('config')->adminUrl."/".$this->directory;
	}
	public function get($name){
		switch ($name) {
			case 'url':
				return $this->url();
				break;
			case 'layout':
				$path = $this->api('config')->paths->admin."layouts/";
				$file = $this->data->getElementsByTagName($name)->item(0)->nodeValue.".php";
				return $path.$file;
				break;
			default:
				return parent::get($name);
				break;
		}
	}

}