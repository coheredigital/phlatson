<?php 

class FieldtypeFields extends Fieldtype{

	private $template;

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.css");
	}
	protected function addScripts(){
		api('config')->scripts->add($this->url."shapeshifter/shapeshifter.js");
		api('config')->scripts->add($this->url."{$this->className}.js");
	}


	public function saveFormat($name, $value){
		$dom = new DomDocument;
		$root = $dom->createElement($name);
		foreach ($value as $key => $value) {
			$node = $dom->createElement("field",$key);
			$node->setAttribute("col", $value);
			$root->appendChild($node);
		}	
		$dom->appendChild($root);
		return $dom->documentElement;
	}


	protected function renderInput(){

		$attributes = $this->getAttributes();

		foreach ($this->value as $field) {

			$columns = trim($field->attributes('col'));

			$output .= "<div data-ss-colspan='{$columns}' class='{$this->className}_fieldItem col_{$columns}' >
							<div class='{$this->className}_fieldContent' >
								<div class='{$this->className}_label label' >
									<a href='#'>{$field->label}</a>
								</div>
								<div class='{$this->className}_name name' >{$field->name}</div>
								<input type='hidden' name='{$this->name}[{$field->name}]' value='{$columns}' >
								<div class='colCount'>columns <span class='colValue'>{$columns}</span></div>
							</div>
		
						</div>";
		}
		$output = "	<div class='{$this->className}_fieldsGrid clearfix'>
						{$output}
						<div class='inputs'></div>
					</div>";
		return $output;
	}

}