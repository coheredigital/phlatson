<?php

class File {

	public $name;
	public $path;
	public $url;
	public $filename;
	public $size;
	public $extension;
	public $type;


	public function __construct($u,$f){

		$path = CONTENT_PATH.$u.DIRECTORY_SEPARATOR;
		$url = CONTENT_URL."/".$u."/";

		$fileInfo = pathinfo($path.$f);



		$this->type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path.$f);
		$this->path = $path;
		$this->url = $url.$f;
		$this->name = $fileInfo['filename'];
		$this->extension = $fileInfo['extension'];
		$this->filename = (string) $f;
		$this->size = filesize($path.$f);
	}


	public function __toString(){
		return $this->url.$this->filename;
	}

}