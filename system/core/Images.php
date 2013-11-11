<?php

class Files extends XData {

	protected $_dataFile = "files.xml";
	protected $position = 0;

	// allows the page array to be counted directly
	public function count() {
		return count($this->_data);
	}




}