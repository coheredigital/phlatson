<?php

/*

Basic data array to access simple arrays like object and provide common funtionality in the api
may not be needed long term, possible that ObjectArray could extend from this to simplify the methods in 
ObjectArray and allow natural fallbacks

 */

abstract class SimpleArray extends Core implements IteratorAggregate{

	protected $data = array();


	public function __set($key, $value) {
		$this->set($key, $value); 
	}
	public function set($key, $value) {
		$this->data[$key] = $value; 
		return $this; 
	}


	public function __get($key) {
		return $this->get($key); 
	}
	public function get($key) {
		if(is_object($key)) $key = "$key"; // stringify $object
		if(isset($this->data[$key])) {
			return $this->data[$key];
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