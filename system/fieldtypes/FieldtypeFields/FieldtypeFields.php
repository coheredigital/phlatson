<?php 

class FieldtypeFields extends Fieldtype{



	protected function renderInput(){
		$attributes = $this->getAttributes();
		foreach ($this->value as $key => $value) {
			$width = 12;
			$output .= "<div>{$key} -- {$value}</div><hr>";
		}
		// $output = "<textarea {$attributes} name='{$this->name}' id='{$id}' cols='30' rows='10'>{$this->value}</textarea>";
		return $output;
	}

}