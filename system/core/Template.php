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
		$layoutFile = is_file($layoutFile) ? $layoutFile : null;
		return $layoutFile;
	}

	public function get($name){
		switch ($name) {
			case 'layout':
				return $this->getLayout();
				break;
			case 'template':
				$template = new Template("template");
				return $template;
				break;
			default:
				return parent::get($name);
				break;
		}
	}
}