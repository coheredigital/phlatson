<?php


class Fields extends Core{

	protected $basePath;
	protected $secondaryPath;

	public function __construct(){

		$this->basePath = $this->api('config')->paths->fields;

	}

	public function get($url){
		$page = new Field($url);
		return $page;
	}

	protected function setBasePath(){
		return api('config')->paths->fields;
	}

	/**
	 * retrieve all fields 
	 * should be made an alias of $fields->find('/'); LATER!
	 */
	public function all(){
		$array = glob($this->basePath."*", GLOB_ONLYDIR);
		$dataArray = array();
		foreach ($array as $value) {
			$dataArray[] = new Field(basename($value));
		}
		return $dataArray;
	}

}