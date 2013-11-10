<?php

class Image extends File{


	public $width;
	public $height;
	public $type;
	public $bits;


	public function __construct($p, $f){
		parent::__construct($p, $f);

		$imageData = getimagesize($this);
		$this->width = $imageData[0];
		$this->height = $imageData[1];
		$this->type = $imageData['mime'];
		$this->type = $imageData['bits'];
	}


}