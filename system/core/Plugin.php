<?php


abstract class Plugin extends DataObject{
	
	protected $dataFolder = "plugins/";


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