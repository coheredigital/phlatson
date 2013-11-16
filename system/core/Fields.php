<?php


class Fields extends Core{


	
	// 'name' => 'value' 	pairing of available fields
	// 'name' => 'path'
	public $data = array();


	/*
	
	Change below system wide to be 

	$sitePath
	$systemPath
	$dataFolder

	so that retrieval uses

	$sitePath.$dataFolder
	--and--
	$systemPath.$dataFolder

	 */
	protected $basePath;
	protected $secondaryPath;

	public function __construct(){
		$this->basePath = $this->api('config')->paths->fields;
		$this->secondaryPath = $this->api('config')->paths->system."/fields/";
		$this->data = $this->load();
	}

	public function get($url){
		$page = new Field($url);
		return $page;
	}



	protected function setBasePath(){
		return api('config')->paths->fields;
	}

	// load availabe fields in $data array()
	protected function load(){
		$siteFields = glob($this->basePath."*", GLOB_ONLYDIR);
		$systemFields = glob($this->secondaryPath."*", GLOB_ONLYDIR);
		$merged = array_merge($siteFields,$systemFields);

		$array = array();
		foreach ($merged as $path) {
			$name = basename($path);
			$array["$name"] = $path;
		}

		return $array;
	} 

	/**
	 * retrieve all fields 
	 * should be made an alias of $fields->find('/'); LATER!
	 */
	public function all(){
		$fieldArray = array();
		foreach ($this->data as $key => $value) {
			$fieldArray[] = new Field($key, $value);
		}
		return $fieldArray;
	}

}