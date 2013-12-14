<?php


abstract class Extension extends DataObject{
	
	protected $dataFolder = "extensions/";


	public function get($name){
		switch ($name) {
			case 'name':
				return $this->className;
			default:
				return parent::get($name);
				break;
		}
	}

}