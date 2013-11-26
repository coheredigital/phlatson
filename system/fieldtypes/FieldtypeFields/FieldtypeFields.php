<?php 

class FieldtypeFields extends Fieldtype{

	private $template;

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/shapeshifter/shapeshifter.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.js");
	}



	protected function renderInput(){

		$attributes = $this->getAttributes();
		foreach ($this->value as $field) {

			$columns = $field->attributes('col');

			$output .= "<div data-columns='{$columns}' data-ss-colspan='{$columns}' class='{$this->className}_fieldItem col_{$columns}' >
							<div class='{$this->className}_fieldContent' >
								<div class='{$this->className}_label label' >
									<a href='#'>{$field->label}</a>
								</div>
								<div class='{$this->className}_name' >
										{$field->name}
								</div>
								<div class='colCount'>columns <span>{$columns}</span></div>
							</div>
		
						</div>";
		}
		$output = "<div class='{$this->className}_fieldsGrid clearfix'>{$output}</div>";
		return $output;
	}

}