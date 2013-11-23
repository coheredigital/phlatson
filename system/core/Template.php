<?php 

class Template extends DataObject{
	protected function setBasePath(){
		return api('config')->paths->templates;
	}

	public function fields(){
		$fieldsArray = $this->find("//field");
		$fields = array();
		foreach ($fieldsArray as $f) {
			$field = new Field($f);
			$attr = $f->attributes();
			foreach ($attr as $key => $value) {
				$field->attributes($key, $value);
			}

			$fields["$field->name"] = $field;

		}

		return $fields;
	}

	private function getLayout(){
		$layoutFile = $this->api('config')->paths->layouts.$this->directory.".php";
		$layoutFile = is_file($layoutFile) ? $layoutFile : null;
		return $layoutFile;
	}

	public function get($name){
		switch ($name) {
			case 'layout':
				return $this->getLayout();
				break;
			default:
				$this->data->{$name};
				break;
		}
		return parent::get($name);
	}
}