<?php

abstract class ObjectArray extends Core implements IteratorAggregate{

	
	// 'name' => 'value' 	pairing of available fields
	// 'name' => 'path'
	public $data = array();

	// the folder within the site and sytem paths to check for items ex: fields, templates, etc
	protected $dataFolder;
	protected $checkSystem = true; // flag whether or not to load values from identical forlder in system directory (ex: false for users | true for fields | defaults to true)

	// used to identify the singular version of the reperesent array 
	// Ex: Fields = Field | Templates = Template , fairly straight forward, used primarily to make code reusable
	protected $singularName;


	// load availabe fields in $data array()
	protected function load(){
		$array = glob($this->api('config')->paths->site.$this->dataFolder."*", GLOB_ONLYDIR);
		if ($this->checkSystem) {
			$array2 = glob($this->api('config')->paths->system.$this->dataFolder."*", GLOB_ONLYDIR);
			$array = array_merge($array,$array2);
		}

		// assign key => value pairs
		$dataArray = array();
		foreach ($array as $path) {
			$name = basename($path);
			$dataArray["$name"] = $path;
		}

		$this->data = $dataArray;
	}


	public function __set($key, $value) {
		$this->set($key, $value); 
	}
	public function set($key, $value) {
		$this->data[$key] = $value; 
		return $this; 
	}

	public function all(){
		$array = array();
		foreach ($this->data as $key => $value) {
			$array[] = new $this->singularName($key, $value);
		}
		return $array;
	}

	public function __get($key) {
		return $this->get($key); 
	}

	public function get($name) {
		if(is_object($name)) $name = (string) $name; // stringify $object
		if(isset($this->data[$name])) {
			$path = $this->data[$name];
			$className = $this->singularName;
			if ($checkSystem) { // if the check system flag is set we should pass $name and $path of object
				$object = new $className($name,$path);
			}
			else{
				$object = new $className($name);
			}
			
			return $object;
		}
		return false;
	}


	public function has($key) {
		return ($this->get($key) !== null); 
	}

	public function remove($key) {
		unset($this->data[$key]);
		return $this;
	}

	public function getIterator() {
		return new ArrayObject($this->data); 
	}

	public function __unset($key) {
		$this->remove($key); 
	}

}
