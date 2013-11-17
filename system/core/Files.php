<?php

/*

Get files recusively through folder by checking the existing "files.xml"

can be set to start at a parent folder ex: "/about-us/"
and can be limited to a recursive depth (0 for no limit | 1 for one, no recursive)

files shoulde be stored in a key => value array where key is /path/to/folder/ and value is filename.ext


 */

class Files extends ObjectArray {

	protected $dataFile = "files.xml";

	public function __construct(){
		$this->dataFolder = "fields/";
		$this->singularName = "File";
		$this->load();
	}

}