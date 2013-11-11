<?php

class FilesFilter {

	private $_filterValue = false;
	private $_filterBy = false;

	function __construct($filterValue, $filterBy = "extension"){
		$this->_filterValue = $filterValue;
		$this->_filterBy = $filterBy;
	}

	function filter($x) {
		// var_dump($x->extension);
		switch ($this->_filterValue) {
			// case 'value':
			// 	# code...
			// 	break;
			
			default:
				return $x->extension == $this->_filterValue;
				break;
		}
       
    }
}