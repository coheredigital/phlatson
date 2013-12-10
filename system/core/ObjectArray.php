<?php

abstract class ObjectArray extends Core implements IteratorAggregate, Countable{
	
	public $data = array();
	protected $count;

	// the folder within the site and sytem paths to check for items ex: fields, templates, etc
	protected $root;
	protected $checkSystem = true; // flag whether or not to load values from identical forlder in system directory (ex: false for users | true for fields | defaults to true)
	protected $allowRootRequest = false;
	// used to identify the singular version of the reperesent array 
	// Ex: Fields = Field | Templates = Template , fairly straight forward, used primarily to make code reusable
	protected $singularName;

	// status flags
	protected $dataLoaded = false;


	final public function __construct(){
		$this->data = $this->getList();
	}


	// load availabe objects into $data array()
	protected function getList(){
		if ($this->root) {
			$array = glob($this->api('config')->paths->site.$this->root."*", GLOB_ONLYDIR);
			if ($this->checkSystem) {
				$array2 = glob($this->api('config')->paths->system.$this->root."*", GLOB_ONLYDIR);
				$array = array_merge($array,$array2);
			}


			// assign key => value pairs
			$dataArray = array();
			foreach ($array as $path) {
				$name = basename($path);
				$dataArray["$name"] = $path;
			}

			return $dataArray;

		}
		
		return null;
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

		if(!isset($this->data[$name]) && !$this->allowRootRequest) return false;
		$object = new $this->singularName($name);
		return $object;
		
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

	public function count(){
		if (!isset($this->count)) $this->count = count($this->data);
		return $this->count;
	}

	public function __unset($key) {
		$this->remove($key); 
	}

}
