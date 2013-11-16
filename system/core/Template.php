<?php 

class Template extends DataObject{


	public function fields(){
		return $this->data->xpath("//field");
	}

	protected function setBasePath(){
		return api('config')->paths->templates;
	}


	private function getLayout(){
		$layoutFile = $this->api('config')->paths->layouts.$this->directory.".php";
		$layoutFile = is_file($layoutFile) ? $layoutFile : null;
		return $layoutFile;
	}


	public function get($name){
		switch ($name) {
			case 'layout':
				$value = $this->getLayout();
				break;
			default:
				// if not caught pass back to parent get() 
				$value = parent::get($name);
				break;
		}
		return $value;
	}


}