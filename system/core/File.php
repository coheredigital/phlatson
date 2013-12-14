<?php

class File extends SimpleArray{

	public function __construct($u,$f){


		$path = $this->api("config")->paths->content.$u;
		$url = $this->api("config")->urls->content.$u;
		$file = $path.$f;
		// $this->dataFile = $f;
		$fileInfo = pathinfo($path.$f);

		$this->type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);
		$this->path = $path;
		$this->url = $url.$f;
		$this->folder = $u;
		$this->name = $fileInfo['filename'];
		$this->extension = $fileInfo['extension'];
		$this->filename = (string) $f;
		$this->file = (string) $file;
		$this->size = filesize($path.$f);
	}


	public function __toString(){
		return $this->url.$this->filename;
	}

}