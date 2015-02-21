<?php

class InputFields extends Input
{

    protected function renderInput()
    {
        app('config')->styles->add($this->url . "{$this->className}.css");
        app('config')->scripts->add($this->url . "{$this->className}.js");

        $fields = app("fields")->all();

        $fieldsSelect = "<option value=''>Choose a field to add...</option>";
        foreach ($fields as $f) {
            $fieldsSelect .= "<option value='$f->name'>$f->name</option>";
        }


        $fieldAdd = "<select>{$fieldsSelect}</select>";

        if ($this->value) {
            foreach ($this->value as $field) {

                // retrieve the field object because "$this->value" will return an unformatted value
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
	                <div class='field-list'>
						{$output}
					</div>";
        return $output;
    }

}