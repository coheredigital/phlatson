<?php

class DataObject extends Core implements Countable, IteratorAggregate {

	public $data;
	protected $basePath;
	protected $dataFile = "data.xml";
	protected $config;

	public $path;
	public $directory;


	public $pageRequest = array();

	function __construct($directory = false){
		$this->config = $this->api('config');
		
		$this->basePath = $this->setBasePath();

		$this->directory = trim($directory, "/");
		$this->pageRequest = $this->getPageRequests($directory);
		$this->path = realpath($this->basePath.$this->directory).DIRECTORY_SEPARATOR;
		$this->data = $this->getXML();

	}



	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}
	public function get($name){
		return $this->data->{$name};
	}

	public function __set($name, $value){
		return $this->set($name, $value);
	}

	public function set($name, $value){
		if ($name && $value && $this->data) {
			$this->data->{$name} = $value;
		}
		
	}

	/**
	 * Return the "directory" for this object, sortof ID in this system
	 */
	public function __toString(){
		return (string) $this->directory;
	}

	/**
	 * Load XML file into data object for access and reference
	 */
	protected function getXML(){
		if (is_file($this->path.$this->dataFile)) {
			return simplexml_load_file($this->path.$this->dataFile);
		}
		return null;
	}


	protected function createUrl($array){
		if (is_array($array) && implode("", $this->pageRequest)) {
			$url = "/".implode("/", $array);
			return $url;
		}
		return null;
	}


	protected function getPageRequests($url){
		$array = explode("/", $url);
		return $array;
	}

	// override this function in descendant classes to set the basepath during construct
	protected function setBasePath(){
		return api('config')->paths->content;
	}



	protected function getTemplate(){
		$templateName = $this->data->template;
		if ($templateName && !isset($this->template)) {
			$template = new Template($templateName);
			$this->template = $template; // cache to $this->template on first request
		}

		return $template;
	}

	public function save(){
		$this->data->asXML($this->path.$this->dataFile);
	}

	// allows the data array to be counted directly
	public function count() {
		return count($this->data);
	}

	// iterate the object data in a foreach
	public function getIterator() {
		return $this->data; 
	}

}
