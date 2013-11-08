<?php


class Page extends XData implements Countable{

	protected $config;

	// define some protected variable to be used by all page objects
	public $template;



	function __construct($url = false){


		$url = trim($url ? $url : $_GET['_url'], "/");
		$this->_request = $this->_requests($url);


		if ($this->_request[0] != "admin") {
			$this->_getPath();


			$this->_file = "{$this->_path}".DIRECTORY_SEPARATOR."content.xml";
			$this->_loadData($this->_file);
			if ($this->_data) $this->_setTemplate($this->_data);


		}
		else {
			$this->_path = null;
			$this->_file = "{$this->_path}".DIRECTORY_SEPARATOR."content.xml";
			$this->template = ADMIN_PATH."index.php";
		}

	}



	protected function _requests($url){
		$array = explode("/", $url);
		return $array;
	}


	protected function _setTemplate($data){
		$this->template = "./site/layouts/{$this->_data->template}.php";
	}



	public function url($full = true){
		$url = implode("/", $this->_request);
		if ($full) {
			$url = SITE_URL."/".$url;
		}
		return $url;
	}




	public function children(){

		$subdirectories = glob("{$this->_path}".DIRECTORY_SEPARATOR."*" , GLOB_ONLYDIR);

		$children = array();
		foreach($subdirectories as $folder) {

			$folder = realpath($folder);

     		$url = str_replace(CONTENT_PATH, '', $folder).DIRECTORY_SEPARATOR;
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


	protected function _formatField($name){

		$value = $this->_data->{$name};
		if (!$value) return false; // return false if node doesn't exist

		$fieldGet = $this->getFieldXML($name);


		if ($fieldGet){
			$fieldtype = (string) $fieldGet->fieldtype;
			$format = (string) $fieldGet->format;
		}

		if($value->attributes) {
			$attributes = $value->attributes();
			$fieldtype = (string) $value->attributes()->fieldtype;
			$format = (string) $value->attributes()->format;
		}

		if ($fieldtype) {
			$field = new $fieldtype( (string) $value, $fieldtype, $format);
			$value = $field;
		}
		else{
			$value = $this->_data->{$name};
		}



		return $value;
	}

	protected function _createUrl($array){

		if (is_array($array) && implode("", $this->_request)) {
			$url = "/".implode("/", $array);
			return $url;
		}

		return false;

	}

	public function getFieldXML($name){
		$file = SITE_PATH."fields/{$name}.xml";
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