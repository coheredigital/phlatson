<?php
/*

Used in the management of Admin Pages and layouts, uses some special feature and overrides that extend from the base Page class

-- override layout discovery methods
-- doesn't require exisiting templates since Admin pages are render using alternative methods

 */

$output = "";

class AdminPage extends Page{

	// define some protected variable to be used by all page objects

	function __construct($url = false){
		parent::__construct($url);

		if ($this->urlRequest[0] == "admin") {
			array_shift($this->urlRequest);
		}

		$path = realpath($this->api('config')->paths->admin."pages/{$this->directory}").DIRECTORY_SEPARATOR;
		$this->setupData($path);

	}


	public function url(){
		return 	$this->api('config')->urls->root.$this->api('config')->adminUrl."/".$this->directory;
	}
	public function get($name){
		switch ($name) {
			case 'extension':
				return $this->getExtension();
				break;
			case 'layout':
				$path = $this->api('config')->paths->admin."layouts/";
				$file = $this->data->layout.".php";
				return $path.$file;
				break;
			default:
				return parent::get($name);
				break;
		}
	}



	protected function getExtension(){
		if ($this->data->extension) {
			$extension = api("extensions")->get("{$this->data->extension}");
			return $extension;
		}
	}

	public function render(){
		if ($this->data->extension) {
			return $this->extension->render();
		}
		return false;
	}

}

