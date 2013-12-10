<?php

abstract class DataObject extends Core implements Countable, IteratorAggregate {

	// private $name;
	public $path;
	protected $data;

	protected $checkSystem = true; // if set to true system should be checked second for named object ex: field checks content folder for "name" field, then finds it in system folder because its a default field. DEFAULT TRUE
	protected $dataFolder;
	protected $dataFile = "data.xml"; // what file name should be check for data

	// what file name should be check for data
	const DATA_FILE = "data.xml";

	public $urlRequest = array();

	function __construct($url){

		$this->urlRequest = $this->getUrlRequest($url);

		$lastRequestIndex = count($this->urlRequest)-1;


		$sitePath = realpath($this->api('config')->paths->site.$this->dataFolder.$this->directory).DIRECTORY_SEPARATOR;
		$systemPath = realpath($this->api('config')->paths->system.$this->dataFolder.$this->directory).DIRECTORY_SEPARATOR;

		if (is_file($sitePath.DataObject::DATA_FILE)) {
			$this->path = $sitePath;
		}
		else if(is_file($systemPath.DataObject::DATA_FILE)){
			$this->path = $systemPath;
		}

		$this->data = $this->getXML();

	}



	/* MAGIC!! */
	public function __get($name){
		// handle / cache class name request
		if ($name == "className") {
			return $this->className();	
		}
		return $this->get($name);
	}

	public function get($name){
		switch ($name) {
			case 'name':
				// return $this->name;
				$lastRequestIndex = count($this->urlRequest)-1;
				return (string) $this->urlRequest[$lastRequestIndex];			
			case 'requests':
				return $this->urlRequest;
				break;
			case 'directory':
				$directory = trim(implode("/", $this->urlRequest), "/");
				return $directory;
			case 'template':
				return $this->getTemplate();
				break;
			case 'templateName':
				return $this->getTemplate();
				break;
			default:
				return $this->getUnformatted($name);
				break;
		}
	}

	// for now basically an XPATH alias
	public function find($name){
		return $this->data->xpath("$name");
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

		$file = $this->path.DataObject::DATA_FILE;

		if (is_file($file)) {
			return simplexml_load_file($file);
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
		$templateName = $this->data->template;
		if ($templateName) {
			$template = new Template($templateName);
		}

		return $template;
	}


	public function save($postData){

		// create a domdoc to store saved values
		$save = new DomDocument;
		// set the formatting if set in config
		if ($this->api("config")->formattedXML) {
			$save->formatOutput = true;
			$save->preserveWhiteSpace = false;
		}
		else{
			$save->formatOutput = false;
			$save->preserveWhiteSpace = false;
		}


		// add the root element
		$root = $save->createElement("root");
		$save->appendChild($root);

		$root = $save->documentElement;

		// loop through the templates available fields so that we only set values 
		// for available feilds and ignore the rest
		$fields = $this->template->fields;
		foreach ($fields as $field) {
			$value = $postData->{$field->name};
			
			$fieldtype = $field->type();

			$formattedValue = $fieldtype->saveFormat( $field->name, $value);
			if ($formattedValue instanceof DomElement) {
				$node = $save->importNode($formattedValue, true);
				$save->documentElement->appendChild($node);
			}
			elseif($formattedValue instanceof DOMDocument){
				$node = $save->importNode($formattedValue->documentElement, true);
				$save->documentElement->appendChild($node);
			}
			else{
				$node = $save->createElement($field->name, $formattedValue);
				$root->appendChild($node);
			}
		}

		// save to file
		// $save->save($this->path.self::DATA_FILE);
		$save->save($this->path."save.xml");

		// destroy $saver
		unset($saver);
	}



	public function getEditable($name){
		if (is_object($name)) $name = (string) $name;
		$value = $this->getFormatted($name, "edit");
		return $value;
	}


	protected function getFormatted($name, $type = "output"){

		// return null if data does not exist
		if (!$this->data) return null;

		// get the field object fatching the passed "$name"
		$field = $this->api("fields")->get("$name");
		// find the corresponding field file and retrieve relevant settings
		$fieldClassname = (string) $field->fieldtype;

		$value = $this->getUnformatted("$name");
		if (!$value) return false; // return false if node doesn't exist


		if ($fieldClassname) {
			$fieldtype = new $fieldClassname( $field );
			$value = $fieldtype->format( $value, $type );
		}

		return $value;
	}

	/**
	 * public alias for getFormatted($name, "raw")
	 * @param  string $name
	 * @return mixed
	 */
	public function getUnformatted($name){

		if (!$this->data) return null;
		else{
			$field = $this->api("fields")->get("$name");
			if ($field) {
				$fieldClass = (string) $field->fieldtype;
			}
			
			// get the field object matching the passed "$name"
			$value = $this->data->{$name};
			return $value;
		}
	}


	public function __set($name, $value){
		return $this->set($name, $value);
	}

	public function set($name, $value){
		if ($name && $value != "") {


		}

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
