<?php

class FieldtypeFields extends Fieldtype
{


    public function getOutput($array)
    {

        $fields = new ObjectArray();

        if (count($array)) {
            foreach ($array as $item) {

                if (!isset($item['name'])) {
                    continue;
                }


                if($field = app("fields")->get($item['name'])) {
                    $fields->add($field);
                } // TODO : this will skip missing / invalid fields, reevaluate validation here

            }
        }

        if($this->object instanceof Object) $defaultFields = $this->object->master->defaultFields;

        if (count($defaultFields)) {
            foreach ($defaultFields as $item) {
                $field = app("fields")->get($item);
                if ($field instanceof Field) {
                    $fields->add($field);
                }
            }
        }

        // attached the reference Object
        $fields->setObject($this->object);
        return $fields;
    }

    public function getSave($array)
    {

        $formattedArray = [];

        // remove invalid fields
        foreach ($array as $key => $name) {
            if (!app("fields")->get($key)) {
                unset($array[$key]);
            } else {
                $formattedArray[] = ["name" => $key];
            }
        }

        return $formattedArray;
    }

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