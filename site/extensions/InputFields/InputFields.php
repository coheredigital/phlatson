<?php

class InputFields extends Input
{


    protected function renderInput()
    {
        api('config')->styles->add($this->url . "{$this->className}.css");
        api('config')->scripts->add($this->url . "{$this->className}.js");

        $fields = api("fields")->all();

        $fieldsSelect = "";
        foreach ($fields as $f) {
            $fieldsSelect .= "<option value='$f->name'>$f->name</option>";
        }


        $fieldAdd = "<select>{$fieldsSelect}</select>";

        if ($this->value) {
            foreach ($this->value as $field) {

                // retrieve the field object because "$this->value" will return an unformatted value
                $field = api("fields")->get($field["name"]);

                $output .= "<div class='item' >
                            <div class='header' >
                                {$field->label}
                            </div>
                            <div>{$field->name}</div>
                            <input type='hidden' name='" . $this->field->name . "[{$field->name}]' value='{$columns}' >
						</div>";
            }
        }

        $output = "	$fieldAdd
	                <div class='{$this->className} ui list selection animated segment'>
						{$output}
					</div>
					<div class='inputs'></div>";
        return $output;
    }

}