<?php

class Finder extends FilterIterator{

    protected $iterator;
	public $filterValue = false;
	public $filterBy = false;

	function __construct(Iterator $iterator, $filterValue, $filterBy = "extension")
	{

		parent::__construct($iterator);

		$this->filterValue = $filterValue;
		$this->filterBy = $filterBy;
	}

    function accept()
    {
        $item = $this->getInnerIterator()->current();

        if($item->{$this->filterBy} == $this->filterValue) return true;
        else return false;
    }

}