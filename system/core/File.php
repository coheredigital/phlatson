<?php

class File extends DataObject{

	// public $name;
	// public $path;
	// public $url;
	// public $filename;
	// public $size;
	// public $extension;
	// public $type;


	public function __construct($u,$f){

		$path = realpath($this->api("config")->paths->site).DIRECTORY_SEPARATOR.$u.DIRECTORY_SEPARATOR;
		$url = $this->api("config")->urls->content.$u."/";
		$file = $path.$f;

		$fileInfo = pathinfo($path.$f);


		$this->type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
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