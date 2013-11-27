<?php

abstract class DataObject extends Core implements Countable, IteratorAggregate {

	public $name;
	public $path;
	protected $data;
	protected $basePath;
	private $className = null;

	protected $checkSystem = true; // if set to true system should be checked second for named object ex: field checks content folder for "name" field, then finds it in system folder because its a default field. DEFAULT TRUE
	protected $dataFolder;
	protected $dataFile = "data.xml"; // what file name should be check for data

	// what file name should be check for data
	const DATA_FILE = "data.xml";

	public $urlRequest = array();

	function __construct($url){


		$this->basePath = $this->setBasePath();
		$this->urlRequest = $this->getUrlRequest($url);


		$lastRequestIndex = count($this->urlRequest)-1;
		$this->name = $this->urlRequest[$lastRequestIndex] ? (string) $this->urlRequest[$lastRequestIndex] : "home";


		$sitePath = $this->api('config')->paths->site.$this->dataFolder.$this->directory."/";
		$systemPath = $this->api('config')->paths->system.$this->dataFolder.$this->directory."/";


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
			if (!isset($this->className)) $this->className = get_class($this);
			return $this->className;	
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
		if ($name && $value != "") {
			// node we are attempting to find
			$node = $this->data->getElementsByTagName($name)->item(0);

			if (is_string($value) && isset($value)) {
				
				if ($node) {
					$this->data->getElementsByTagName($name)->item(0)->nodeValue = $value;
				}
				else{
					// create a node for the value with the requested name
					$dom = new DOMDocument('1.0', 'utf-8');
					$dom->formatOutput = true;

					$node = $dom->createElement($name, $value);
					$node = $this->data->importNode($node, true);
					$this->data->documentElement->appendChild($node);
				}
				
			}
			else if($value instanceof DomElement){
				// if node exists
				if ($node) {
					// replace children
					$this->data->formatOutput = true;
					$value = $this->data->importNode($value, true);
					$this->data->documentElement->replaceChild($value, $node);
				}
				else{ // add a new node otherwise

				}
			}

		}
		return false;
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

		// var_dump($this->path.DataObject::DATA_FILE);

		if (is_file($this->path.DataObject::DATA_FILE)) {
			$dom = new DomDocument();
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->load($this->path.DataObject::DATA_FILE);
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


	public function save($postData){

		// clone self so we can safely overwrite values
		$saver = clone $this; 
		$saver->data->loadXML($this->data->saveXML());

		$save = new DomDocument;
		$root = $save->createElement("root");
		$save->appendChild($root);

		$root = $save->documentElement;

		// loop through the templates available fields so that we only set values 
		// for available feilds and ignore the rest
		foreach ($this->template->fields() as $field) {
			$value = $postData->{$field->name};
			

			$fieldtype = $field->type();
			$value = $fieldtype->saveFormat($value);

			$node = $save->createElement($field->name, $value);
			$root->appendChild($node);
			// $save->set("$field->name", $value);
		}

		// loop through the templates available fields so that we only set values 
		// for available feilds and ignore the rest
		foreach ($this->template->fields() as $field) {
			$value = $postData->{$field->name};
			$fieldtype = $field->type();
			$value = $fieldtype->saveFormat($value);
			$saver->set("$field->name", $value);
		}

		// save to file
		$save->save($this->path."new_save.xml", LIBXML_NOEMPTYTAG);
		$saver->data->save($this->path."old_save.xml", LIBXML_NOEMPTYTAG);

		// destroy $saver
		unset($saver);
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
