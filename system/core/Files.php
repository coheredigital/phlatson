<?php

class Files extends DataObject {

	protected $_dataFile = "files.xml";
	protected $_fileType = false;
	protected $position = 0;


	function __construct($url = false){
		
		parent::__construct($url);
		
		// collect the files
		$files = array();
		foreach ($this->_data as $item) {
			$files[] = new File($this->url(false), $item->filename);
		}

		$this->_data = new ArrayIterator( $files );
		if ($this->_fileType) $this->_data = new Filter($this->_data, $this->_fileType);

	}

	public function find($selector){

		if (strrpos($selector, "=")) {
			$filter = explode("=", $selector);
			$filterBy = (string) $filter[0];
			$filterValue = (string) $filter[1];

			$data = $this->_data;
			$data = new Filter($data, $filterValue, $filterBy);

			$files = array();
			foreach ($data as $x) {
				$files[] = $x;
			}
			$data = $files;
			return $data;

		}

		return false;

	}

	public function updateList(){




	}


}