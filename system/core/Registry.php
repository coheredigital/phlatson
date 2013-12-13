<?php 

/**
 * API Registry class contains all core api classes to be accessed by any
 * class extending the core. or any class via the api() function defined in _functions.php
 */


final class Registry implements IteratorAggregate{

	private $registry = array();
	private $lock = array();


	/**
	 * set registry API by key
	 * @param string $key
	 * @param object $value
	 */
 	public function set($key, $value, $lock = false) {


		if (isset($this->registry[$key]) && in_array($key, $this->lock)) {
			throw new Exception("There is already an API entry for '{$key}', value is locked.");
		}
		// set $key and $value the same to avoid duplicates
		if($lock) $this->lock[$key] = $key;

		$this->registry[$key] = $value;

   	}

   	/**
   	 * get registry by key
   	 * @param  string $key
   	 * @return object 
   	 */
	public function get($key) {
      	if (!isset($this->registry[$key])) {
        	throw new Exception("There is no '{$key}' entry in the API registry!" );
      	}
      	return $this->registry[$key];
   	}
   	public function __get($key) {
		return $this->get($key);
	}

	public function getIterator() {
		return new ArrayObject($this->registry); 
	}

}
