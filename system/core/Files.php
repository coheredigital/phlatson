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
		// filter the files if a filetype is set
		if ($this->_fileType) {
			$files = array_filter($files, array(new FilesFilter("jpg"), 'filter'));
		}

		// overwrite _data with file object array
		$this->_data = new ArrayIterator( $files );
	}

	public function find($find){
		$array = $this->_files;


		return $array;
	}

	public function updateList(){




	}


}