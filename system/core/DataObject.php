<?php

abstract class DataObject extends Core implements Countable, IteratorAggregate {

	public $data = array();
	protected $basePath;
	protected $className;

	protected $checkSystem = true; // if set to true system should be checked second for named object ex: field checks content folder for "name" field, then finds it in system folder because its a default field. DEFAULT TRUE
	protected $dataFolder;
	protected $dataFile = "data.xml"; // what file name should be check for data, most classes will stick with "data.xml"


	public $path;


	public $urlRequest = array();

	function __construct($url = false, $path = false){

		$this->className = $this->className();

		$this->basePath = $this->setBasePath();
		$this->urlRequest = $this->getUrlRequest($url);


		if ($path === false) {
			$this->path = $this->basePath.$this->getDirectory()."/";
		}
		else{
			$this->path = $path."/";
		}
		
		$this->data = $this->getXML();
		
	}



	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}
	public function get($name){
		switch ($name) {
			case 'name':
				return $this->data->{$name} ? $this->data->{$name} : $this->directory;
				break;
			case 'directory':
				return $this->getDirectory();
			default:
				return $this->data->{$name};
				break;
		}		
	}

	// for now basically an XPATH alias
	public function find(){
		return $this->data->xpath("$name");
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
		if (is_array($array) && implode("", $this->urlRequest)) {
			$url = "/".implode("/", $array);
			return $url;
		}
		return null;
	}


	protected function getUrlRequest($url){
		$array = explode("/", $url);
		return $array;
	}

	// uses the "urlRequest" array to determine what relative folder we are in
	protected function getDirectory(){
		$value = implode("/", $this->urlRequest);
		return (string) trim($value, "/");
	}

	// override this function in descendant classes to set the basepath during construct
	protected function setBasePath(){
		return $this->api('config')->paths->content;
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
