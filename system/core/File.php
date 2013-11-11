<?php

class File{

	public $name;
	public $path;
	public $url;
	public $filename;
	public $size;
	public $extension;


	public function __construct($u,$f){

		$path = CONTENT_PATH.$u.DIRECTORY_SEPARATOR;
		$url = CONTENT_URL."/".$u."/";

		$fileInfo = pathinfo($path.$f);

		$this->path = $path;
		$this->url = $url.$f;
		$this->name = $fileInfo['filename'];
		$this->extension = $fileInfo['extension'];
		$this->filename = $f;
		$this->size = filesize($path.$f);

		// if ($this->extension == "jpg") $this = new Image();
	}


	public function __toString(){

		return $this->path.$this->filename;
	}

}