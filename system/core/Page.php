<?php


class Page{


	// define some protected variable to be used by all page objects
	protected $_data;
	protected $_path;
	protected $_file;
	protected $_request;


	public $template;




	function __construct($url = false){

		$url = trim($url ? $url : $_GET['_url'], "/");
		$this->_request = $this->_requests($url);


		if ($this->url != "admin") {
			$this->_path = $this->url ? str_replace(DIRECTORY_SEPARATOR, '/', CONTENT_PATH.$this->url(false).DIRECTORY_SEPARATOR) : str_replace(DIRECTORY_SEPARATOR, '/', CONTENT_PATH);


			$this->_file = "{$this->_path}content.xml";
			if (is_file($this->_file)) {
				$this->_data = simplexml_load_file($this->_file);
			}
			if ($this->_data) $this->_setTemplate($this->_data);
		}
		else {
			$this->_path = null;
			$this->_file = "{$this->_path}content.xml";
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

		$subdirectories = glob("{$this->_path}*" , GLOB_ONLYDIR);

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



	public function parent(){

		// $folder = dirname($this->_path);
		// $folder = realpath($folder).DIRECTORY_SEPARATOR;

		// if (strlen($folder >= CONTENT_PATH)) {
	 // 		$url = str_replace(CONTENT_PATH, '', $folder).DIRECTORY_SEPARATOR;
	 // 		$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
	 // 		$url = ltrim($url, DIRECTORY_SEPARATOR);

	 // 		$page = new Page($url);
		// }
 	// 	else $page = false;




		$requests = $this->_request;
		array_pop($requests);

		$page = new Page($url);
      	return $page;
	}


	public function rootParent(){

		$folder = dirname($this->_path);
		$folder = realpath($folder).DIRECTORY_SEPARATOR;

		if (strlen($folder >= CONTENT_PATH)) {
	 		$url = str_replace(CONTENT_PATH, '', $folder).DIRECTORY_SEPARATOR;
	 		$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
	 		$url = ltrim($url, DIRECTORY_SEPARATOR);

	 		$page = new Page($url);
		}
 		else $page = false;

      	return $page;
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

	protected function createUrl($array){

 		$url = implode("/", $array);
 		if ($url) {
 			$url = "/".$url;
 		}
 		else
 		

 		return $url;
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
			case 'url':
				$value = $this->url();
				break;
			default:
				$value = $this->_formatField($name);
				break;
		}
		return $value;
	}

	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}

	public function __toString(){
		return (string) $this->url;
	}

}