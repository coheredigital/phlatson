<?php

class Filter extends FilterIterator{

    protected $iterator;
	private $_filterValue = false;
	private $_filterBy = false;

	function __construct(Iterator $iterator, $filterValue, $filterBy = "extension"){

		parent::__construct($iterator);

		$this->_filterValue = $filterValue;
		$this->_filterBy = $filterBy;
	}

    function accept() {
        $file = $this->getInnerIterator()->current();
        if($file->{$this->_filterBy} != $this->_filterValue) return false;
        else return true;
    }

}