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

		$this->path = $this->basePath.$this->directory."/";
		$this->data = $this->getXML();
	}

	protected function setBasePath(){
		return $this->api('config')->paths->admin."content/";
	}

	protected function setBaseUrl(){
		return api('config')->urls->root.$this->api('config')->adminUrl."/";
	}

	public function get($name){
		switch ($name) {
			case 'layout':
				$value = (string) $this->api('config')->paths->admin."layouts/".$this->data->layout.".php";
				break;
			default:
				$value = parent::get($name);
				break;
		}
		return $value;
	}

}