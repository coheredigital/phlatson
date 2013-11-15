<?php


class Fields extends Core{

	protected $basePath;

	public function __construct(){

		$this->basePath = $config->paths->fields;

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
		$array = glob($this->basePath, GLOB_ONLYDIR);
		var_dump($aray);
	}

}