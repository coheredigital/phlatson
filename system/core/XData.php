<?php

class XData implements Countable, IteratorAggregate {

	/**
	 * Holds XML data object
	 */

	public $_data;
	protected $_path;
	protected $_basePath = CONTENT_PATH;
	protected $_dataFile = "data.xml";


	protected $maxDepth = 0; // 0 is unlimited
	protected $_file;
	protected $_request;



	function __construct($url = false){

		$this->_requests($url);
		$this->_path = $this->_getPath();

		$this->_file = $this->_path.$this->_dataFile;
		$this->_data = $this->_loadData();
		if ($this->_data) 
			$this->layout = $this->_setTemplate();
	
	}



	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}

	public function __toString(){
		return (string) $this->url;
	}

	protected function _loadData(){
		if (is_file($this->_file)) {
			return simplexml_load_file($this->_file);
		}
		return null;
	}



	protected function _createUrl($array){

		if (is_array($array) && implode("", $this->_request)) {
			$url = "/".implode("/", $array);
			return $url;
		}

		return false;

	}


	public function url($full = true){
		$url = implode("/", $this->_request);
		if ($full) {
			$url = SITE_URL."/".$url;
		}
		return trim($url,"/");
	}


	protected function _requests($url){
		$array = explode("/", $url);
		$this->_request = $array;
	}


	protected function _getPath(){
		$path = realpath($this->_basePath.$this->url(false)).DIRECTORY_SEPARATOR;
		return $path;
	}


	protected function _setTemplate(){
		$file = realpath(LAYOUTS_PATH.$this->_data->template.".php");
		$file = is_file($file) ? $file : LAYOUTS_PATH."default.php";
		return $file;
	}



	public function get($name){
		$value = $this->_data->$name;
		return $value;
	}


	// allows the data array to be counted directly
	public function count() {
		return count($this->_data);
	}

	// iterate the object data in a foreach
	public function getIterator() {
		return $this->_data; 
	}

}
