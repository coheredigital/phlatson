<?php 

class Template extends DataObject{
	protected $dataFolder = "templates/";

	protected function setBasePath(){
		return api('config')->paths->templates;
	}

	public function fields(){
		$fieldsArray = $this->find("//field");

		$fields = array();
		foreach ($fieldsArray as $f) {
			$field = new Field($f->nodeValue);
			$attr = $f->attributes;
			foreach ($attr as $a) {
				$field->attributes($a->nodeName, $a->nodeValue);
			}

			$fields["$field->name"] = $field;

		}

		return $fields;
	}

	/* this needs a better method */
	public function getTemplate(){
		$template = new Template("template");
		return $template;
	}

	private function getLayout(){
		$layoutFile = $this->api('config')->paths->layouts.$this->name.".php";
		var_dump($layoutFile);
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