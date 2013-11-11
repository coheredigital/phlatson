<?php


class Page extends XData{

	// define some protected variable to be used by all page objects
	public $layout;


	function __construct($url = false){
		
		parent::__construct($url);

		// handle admin page request
		if ($this->_request[0] == "admin") {
			$this->_path = null;
			$this->_file = null;
			$this->template = null;
			$this->layout = ADMIN_PATH."index.php";
		}

	}




	public function children(){

		// get all subfolder of current page path
		$subs = glob($this->_path.DIRECTORY_SEPARATOR."*" , GLOB_ONLYDIR);

		$children = array();
		foreach($subs as $folder) {
			$folder = basename($folder);
     		$url = $this->url(false)."/".$folder;

     		$page = new Page($url);
     		$children[] = $page;

      	}
      	return $children;
	}

	public function parent(){
		$requests = $this->_request;
		array_pop($requests); // remove current (last) item to find parent

		$url = $this->_createUrl($requests);

		if ($url) {
			$page = new Page($url);
	      	return $page;
		}
		return false;
	}


	public function parents(){
		$requests = $this->_request;
		$parents = array();
		$urls = array();

		for ($x=count($requests); $x > 0; $x--) { 
			array_pop($requests);
			$urls[] = $this->_createUrl($requests);
			
		}

		foreach ($urls as $url) {
     		$page = new Page($url);
     		$parents[] = $page;
		}

		return array_reverse($parents);
	}



	public function rootParent(){

		$url = $this->_request[0];

		if ($this->url(false) == $url) {
			return $this;
		}
		elseif ($url) {
			$page = new Page($url);
	      	return $page;
		}
		return false;
	}



	public function files(){

		$files = new Files($this->url(false));
		return $files;

	}

	public function images(){

		$files = new Images($this->url(false));
		return $files;

	}


	protected function _formatField($name){

		$field = new Field($name);

		$value = $this->_data->{$name};
		if (!$value) return false; // return false if node doesn't exist


		// find the corresponding field file and retrieve relevant settings
		
		$fieldClassname = (string) $field->fieldtype;
		$fieldFormat = (string) $field->format;
		

		// override default value for field based on attributes
		if($value->attributes) {
			$attr = $value->attributes();
			$fieldClassname = (string) $attr->fieldtype;
			$fieldFormat = (string) $attr->format;
		}

		if ($fieldClassname) {
			$fieldtype = new $fieldClassname( (string) $value, $fieldClassname, $fieldFormat);
			return $fieldtype;
		}
		else return $this->_data->{$name};
	}


	public function render(){
		return include $this->layout;
	}


	public function get($name){
		switch ($name) {
			case 'children':
				$value = $this->children();
				break;
			case 'parent':
				$value = $this->parent();
				break;
			case 'rootParent':
				$value = $this->rootParent();
				break;
			case 'url':
				$value = $this->url();
				break;
			case 'files':
				$value = $this->files();
				break;
			case 'images':
				$value = $this->images();
				break;
			default:

				$value = $this->_formatField($name);
				break;
		}
		return $value;
	}



}