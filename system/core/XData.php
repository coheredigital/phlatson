<?php

class XData extends X{

	/**
	 * Holds XML data object
	 */

	protected $_data;
	protected $_path;
	protected $_file;
	protected $_request;

	/* MAGIC!! */
	public function __get($name){
		return $this->get($name);
	}

	public function __toString(){
		return (string) $this->url;
	}

	protected function _loadData(){
		if (is_file($this->_file)) {
			$this->_data =  simplexml_load_file($this->_file);
		}
		return false;
	}

	/**
	 * Provides direct reference access to retrieve values in the $data array
	 *
	 * If the given $key is an object, it will cast it to a string. 
	 * If the given key is a string with "|" pipe characters in it, it will try all till it finds a value. 
	 *
 	 * @param string|object $key
	 * @return mixed|null Returns null if the key was not found. 
	 *
	 */
	public function get($key) {
		if(is_object($key)) $key = "$key";
		if(array_key_exists($key, $this->_data)) return $this->_data[$key]; 

		return parent::__get($key); // back to Wire
	}

	protected function _getPath(){

		if ( $this->url ) {
			$path = CONTENT_PATH.$this->url(false);
			$path = realpath($path);
			$this->_path = $path;
		}
		else{
			$this->_path = str_replace(DIRECTORY_SEPARATOR, '/', CONTENT_PATH);
		}

	}



}
