<?php

abstract class DataObject extends Core implements Countable, IteratorAggregate {

	protected $data;
	// protected $saveData;

	protected $basePath;
	protected $className;

	protected $checkSystem = true; // if set to true system should be checked second for named object ex: field checks content folder for "name" field, then finds it in system folder because its a default field. DEFAULT TRUE
	protected $dataFolder;
	protected $dataFile = "data.xml"; // what file name should be check for data, most classes will stick with "data.xml"


	public $name;
	public $path;
	// public $directory;


	public $urlRequest = array();

	function __construct($url){

		$this->className();
		$this->basePath = $this->setBasePath();
		$this->urlRequest = $this->getUrlRequest($url);
		// $this->directory = trim($url);

		$lastRequestIndex = count($this->urlRequest)-1;
		$this->name = $this->urlRequest[$lastRequestIndex] ? (string) $this->urlRequest[$lastRequestIndex] : "home";


		$sitePath = $this->api('config')->paths->site.$this->dataFolder.$this->directory."/";
		$systemPath = $this->api('config')->paths->system.$this->dataFolder.$this->directory."/";
		// var_dump($this->directory);
		// var_dump($this->urlRequest);
		// var_dump($sitePath);
		if (is_file($sitePath.$this->dataFile)) {
			$this->path = $sitePath;
		}
		else if(is_file($systemPath.$this->dataFile)){
			$this->path = $systemPath;
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
				// return $this->name;
				$lastRequestIndex = count($this->urlRequest)-1;
				return (string) $this->urlRequest[$lastRequestIndex];
			case 'directory':
				return trim(implode("/", $this->urlRequest), "/");
			case 'template':
				return $this->getTemplate();
				break;
			default:
				return $this->data->getElementsByTagName($name)->item(0)->nodeValue;
				break;
		}
		return $value;
	}

	// for now basically an XPATH alias
	public function find($name){

		$xpath = new DOMXPath($this->data);
		return $xpath->query($name);

		// return $this->data->getElementsByTagName($name);
		// return $this->data->xpath("$name");
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

		// var_dump($this->path.$this->dataFile);

		if (is_file($this->path.$this->dataFile)) {
			$dom = new DomDocument();
			$dom->load($this->path.$this->dataFile);
			return $dom;
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
		$url = rtrim( (string) $url, '/');
		$array = array();
		if (strpos($url, "/") !== false) {
			$array = explode("/", $url);
		}
		else{
			$array[] = $url;
		}
		return $array;
	}



	public function getTemplate(){
		// $templateName = $this->data->template;
		$templateName = $this->data->getElementsByTagName("template")->item(0)->nodeValue;
		// var_dump($templateName);
		if ($templateName) {
			$template = new Template($templateName);
		}

		return $template;
	}

	// override this function in descendant classes to set the basepath during construct
	protected function setBasePath(){
		return $this->api('config')->paths->content;
	}


	public function save($input){
		// clone the object so we can safely overwrite values
		$this->saveDate = clone $this->data;
		$template = $this->getTemplate();

		foreach ($template->fields() as $f) {

			$field = new Field("$f->nodeValue");
			// var_dump($field);
			$value = $input->{$field->name};



			$fieldtype = $field->type();
			$value = $fieldtype->saveFormat($value);

			$this->saveDate->{$field->name} = $value;

		}

		$saveData = new DomDocument("1.0");
		$saveData->preserveWhiteSpace = false;
		$saveData->formatOutput = true;
		$saveData->loadXML($this->data->asXML());
		$saveData->save($this->path."save.xml");

	}


	public function getEditable($name){
		$value = $this->formatField($name, "edit");
		return $value;
	}


	protected function formatField($name, $type = "output"){

		$field = new Field($name);

		$value = $this->{$name};
		if (!$value) return false; // return false if node doesn't exist


		// find the corresponding field file and retrieve relevant settings
		$fieldClassname = (string) $field->fieldtype;
		$fieldFormat = (string) $field->format;

		if ($fieldClassname) {
			$fieldtype = new $fieldClassname( );
			// $value = $fieldtype->outputFormat( $value, $fieldFormat);
			$value = $fieldtype->format( $value, $type );
		}

		return $value;
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
