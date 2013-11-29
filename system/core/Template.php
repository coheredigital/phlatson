<?php 

/*

	Template extends fieldgroup because its is essentially a fieldgroup with a layout and more settings

 */


class Template extends Fieldgroup{
	protected $dataFolder = "templates/";


	/* this needs a better method */
	public function getTemplate(){
		$template = new Template("template");
		return $template;
	}

	private function getLayout(){
		$layoutFile = $this->api('config')->paths->layouts.$this->name.".php";
		// var_dump($layoutFile);
		$layoutFile = is_file($layoutFile) ? $layoutFile : null;
		return $layoutFile;
	}

	public function get($name){
		switch ($name) {
			case 'layout':
				return $this->getLayout();
				break;
			default:
				return parent::get($name);
				break;
		}
	}
}