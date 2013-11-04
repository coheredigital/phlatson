<?php

class XData{

	protected $_data = array();

	/**
	 * Set a value
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 *
	 */
	public function set($key, $value) {
		if($key == 'data') {
			if(!is_array($value)) $value = (array) $value;
			return $this->setArray($value);
		}
		$v = isset($this->_data[$key]) ? $this->_data[$key] : null;
		$this->_data[$key] = $value;
		return $this;
	}


	/**
	 * Set an array of key=value pairs
	 *
	 * @param array $data
	 * @return $this
	 * @see set()
	 *
	 */
	public function setArray(array $data) {
		foreach($data as $key => $value) $this->set($key, $value);
		return $this;
	}

	/**
	 * Provides direct reference access to set values in the $data array
	 *
	 */
	public function __set($key, $value) {
		$this->set($key, $value);
	}

}