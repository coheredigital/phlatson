<?php

class File{

	public $name;
	public $path;
	public $filename;
	public $size;
	public $extension;


	public function __construct($p,$f){

		$fileInfo = pathinfo($p.$f);

		$this->path = $p;
		$this->name = $fileInfo['filename'];
		$this->extension = $fileInfo['extension'];
		$this->filename = $f;
		$this->size = filesize($p.$f);

		// if ($this->extension == "jpg") $this = new Image();
	}


	public function __toString(){

		return $this->path.$this->filename;
	}

}