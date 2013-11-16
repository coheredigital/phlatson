<?php

class FieldtypeSelect extends Fieldtype{


	public function render($name, $value){
		return "<select class='field-input ".get_class($this)."' name='{$name}' id='Input_{$name}'><option value='CAN'>Canada</option><option value='USA'>United States</option></select>";
	}

}