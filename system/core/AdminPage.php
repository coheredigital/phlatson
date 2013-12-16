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


		$this->path = $this->api('config')->paths->admin."pages/".$this->directory.DIRECTORY_SEPARATOR;
		$this->data = $this->getXML();
	}


	public function url(){
		return 	$this->api('config')->urls->root.$this->api('config')->adminUrl."/".$this->directory;
	}
	public function get($name){
		switch ($name) {
			case 'url':
				return $this->url();
				break;
			case 'extension':
				return $this->getExtension();
				break;
			case 'layout':
				$path = $this->api('config')->paths->admin."layouts/";
				$file = $this->data->$name.".php";
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
			$page = api("pages")->get(api("input")->get->page);
			$extension->setPage($page);
			return $extension;
		}

	}

	public function render(){
		return $this->extension->render();
	}

}