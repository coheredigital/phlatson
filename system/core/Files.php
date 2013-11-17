<?php

/*

Get files recusively through folder by checking the existing "files.xml"

can be set to start at a parent folder ex: "/about-us/"
and can be limited to a recursive depth (0 for no limit | 1 for one, no recursive)

files shoulde be stored in a key => value array where key is /path/to/folder/ and value is filename.ext


 */

class Files extends ObjectArray {

	protected $dataFile = "files.xml";
	protected $dataFolder = "content/";
	protected $checkSystem = true;
	protected $singularName = "File";
	protected $depthLimit = 1;

	public function __construct(){
		$this->load();
	}


	public function load(){
		$path = $this->api('config')->paths->site.$this->dataFolder;
		$directory = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
		$iterator = new RecursiveIteratorIterator($directory);
		$regex = new RegexIterator($iterator, "/{$this->dataFile}/");


		$fileList = array();
		foreach ($regex as  $key => $value) {

			$key = str_replace($this->api('config')->paths->site, "", $key);
			$key = str_replace($this->dataFile, "", $key);
			$key = (string) trim($key, "\\");

			$fileList["$key"] = simplexml_load_file($value->getRealPath());
		}
		// return $fileList;
		$array = array();
		foreach ($fileList as $xml) {
			// var_dump($xml);
			foreach ($xml->xpath("//file") as $k => $v) {
				$array[] = new File("$key","$v->filename");

				// $array["$v->filename"] = (string) "$key";
			}
		}
		$this->data = $array;
	}




}