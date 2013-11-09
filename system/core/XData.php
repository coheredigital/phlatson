<?php

class XData{

	/**
	 * Holds XML data object
	 */

	public $_data;
	protected $_path;
	protected $_basePath = null;

	protected $maxDepth = 0; // 0 is unlimited
	protected $_file;
	protected $_request;



	function __construct($url = false){

		$this->_path = null;
		
		$this->template = ADMIN_PATH."index.php";

		$this->_loadFile($url);
		$this->_loadData($this->_file);
	
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
			$this->_data =  simplexml_load_file($this->_file);
		}
		return false;
	}

	protected function _loadFile($url){
		$this->_file = $this->_basePath.$url.DIRECTORY_SEPARATOR."data.xml";
	}

	public function url($full = true){
		$url = implode("/", $this->_request);
		if ($full) {
			$url = SITE_URL."/".$url;
		}
		return $url;
	}

	protected function _getPath(){

		if ( $this->url ) {

			$path = realpath($this->_basePath.$this->url(false));
			$this->_path = $path;
		}
		else{
			$this->_path = $this->_basePath;
		}

	}


	protected function _setTemplate(){
		$file = realpath(LAYOUTS_PATH.$this->_data->template.".php");
		$file = is_file($file) ? $file : LAYOUTS_PATH."default.php";
	

		$this->layout = $file;
	}



	public function get($name){
		switch ($name) {
			default:

				$value = $this->_data->$name;
				break;
		}
		return $value;
	}



}
