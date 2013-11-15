<?php

/*
	
	Searchs for results extending from $basePath DataArray or object where applicable

*/

class DataFinder extends Core{
	protected $data = array(); 



	public function get($url){
		$page = new Page($url);
		return $page;
	}

}
