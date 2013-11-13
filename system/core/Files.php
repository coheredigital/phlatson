<?php

class Files extends XData {

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
		if ($this->_fileType) $this->_data = new FilesFilter($this->_data, $this->_fileType, "");

	}

	public function find($find){
		$array = $this->_files;


		return $array;
	}

	public function updateList(){




	}


}