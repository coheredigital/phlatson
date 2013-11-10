<?php


class Page extends XData implements Countable{

	protected $config;
	protected $_basePath = CONTENT_PATH;

	// define some protected variable to be used by all page objects

	public $layout;


	function __construct($url = false){


		$url = trim($url ? $url : $_GET['_url'], "/");
		$this->_requests($url);


		if ($this->_request[0] != "admin") {
			$this->_getPath();


			$this->_file = $this->_path.DIRECTORY_SEPARATOR."data.xml";
			$this->_loadData($this->_file);
			if ($this->_data) $this->_setTemplate();


		}
		else {
			$this->_path = null;
			$this->_file = $this->_path.DIRECTORY_SEPARATOR."data.xml";
			$this->template = "admin";
			$this->layout = ADMIN_PATH."index.php";
			
		}


	}




	public function children(){

		$subdirectories = glob("{$this->_path}".DIRECTORY_SEPARATOR."*" , GLOB_ONLYDIR);

		$children = array();
		foreach($subdirectories as $folder) {

			$folder = realpath($folder);

     		$url = str_replace($this->_basePath, '', $folder).DIRECTORY_SEPARATOR;
     		$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
     		$url = ltrim($url, DIRECTORY_SEPARATOR);

     		$page = new Page($url);
     		$children[] = $page;


      	}
      	return $children;
	}

	// allows the page array to be counted directly
	public function count() {
		return count($this->children());
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



	protected function _requests($url){
		$array = explode("/", $url);
		$this->_request = $array;
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

		$files = scandir($this->_path);
		$array = array();

		foreach ($files as $f) {
			if (is_file($this->_path.$f)) {
				$fileInfo = pathinfo($this->_path.$f);
				if ($fileInfo["extension"] == "jpg")
					$array[] = new Image($this->_path,$f);
				else
					$array[] = new File($this->_path,$f);

			}
				
		}


		if (count($array))
			return $array;

		return false;
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

	protected function _createUrl($array){

		if (is_array($array) && implode("", $this->_request)) {
			$url = "/".implode("/", $array);
			return $url;
		}

		return false;

	}

	public function getFieldXML($name){
		$file = SITE_PATH."fields/{$name}/data.xml";
		if (is_file($file))
			return simplexml_load_file($file);
	}


	public function render(){
		return include $this->template;
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
			default:

				$value = $this->_formatField($name);
				break;
		}
		return $value;
	}



}