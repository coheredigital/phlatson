<?php

abstract class DataObject extends Core implements Countable, IteratorAggregate {

	public $data = array();
	protected $basePath;
	protected $className;

	protected $dataReader;

	protected $checkSystem = true; // if set to true system should be checked second for named object ex: field checks content folder for "name" field, then finds it in system folder because its a default field. DEFAULT TRUE
	protected $dataFolder;
	protected $dataFile = "data.xml"; // what file name should be check for data, most classes will stick with "data.xml"


	public $path;
	public $directory;


	public $pageRequest = array();

	function __construct($directory = false, $path = false){

		$this->className = $this->className();

		$this->dataReader = new XMLReader;


		$this->basePath = $this->setBasePath();
		$this->pageRequest = $this->getPageRequests($directory);

		if (!$path) {
			$this->directory = trim($directory, "/");
			$this->path = $this->basePath.$this->directory."/";
		}
		else{
			$this->directory = trim(basename($directory), "/");
			$this->path = $path."/";
		}
		
		$this->data = $this->getXML();

		if (is_file($this->path.$this->dataFile)) {
			$this->dataReader->open($this->path.$this->dataFile);
		}
		
	}



	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}
	public function get($name){
		if ($name == "name") {
			// if item doesn't have a formal "name" field, fall back to directory
			// the two value should ALWAYS be EXACTLY the same and though a name
			// field should always be defined, a fallback is good
			return $this->data->{$name} ? $this->data->{$name} : $this->directory;
		}
		else{
			// if string begins with "/" we will use get as an xpath alias
			if (substr($name, 0, 1) == "/") {
				return $this->data->xpath("$name");
			}
			else{
				return $this->data->{$name};
			}
		}
		
	}

	public function __set($name, $value){
		return $this->set($name, $value);
	}

	public function set($name, $value){
		if ($name && $value && $this->data) {
			$this->data->{$name} = $value;
		}
		
	}

	protected function className(){
		if (!isset($this->className)) $this->className = get_class($this);
		return $this->className;
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