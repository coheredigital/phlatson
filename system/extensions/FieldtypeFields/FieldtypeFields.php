<?php

class FieldtypeFields extends Fieldtype
{

    protected function setup()
    {

    }


    public function getOutput($array)
    {

        $fields = new ObjectArray();

        if( count($array) ) foreach ($array as $item){

            if (!isset($item['name'])) continue;

            $field = api::get("fields")->get($item['name']);
            $fields->add($field);
        }

        if( $this->object instanceof Object && count( $this->object->defaultFields ) ){
            foreach ($this->object->defaultFields as $item){
                $field = api::get("fields")->get($item);
                if ( $field instanceof Field ) $fields->add($field);
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
            if (!api::get("fields")->get($key)) unset($array[$key]);
            else{
                $formattedArray[] = ["name" => $key];
            }
        }

        return $formattedArray;
    }


    protected function renderInput()
    {
        api::get('config')->styles->add($this->url . "{$this->className}.css");
        api::get('config')->scripts->add($this->url . "{$this->className}.js");
        $attributes = $this->getAttributes();

        $fields = api::get("fields")->all();
        $fieldsSelect = api::get("extensions")->get("FieldtypeSelect");

        $fieldsSelect->setOptions($fields);

        $fieldAdd = $fieldsSelect->render();

        if($this->value) foreach ($this->value as $field) {

            // retrieve the field object because "$this->value" will return an unformatted value
            $field = api::get("fields")->get($field["name"]);

            $output .= "<div class='item' >
                            <div class='header' >
                                {$field->label}
                            </div>
                            <div>{$field->name}</div>
                            <input type='hidden' name='" . $this->field->name . "[{$field->name}]' value='{$columns}' >
						</div>";
        }

        $output = "	$fieldAdd
	                <div class='{$this->className} ui list selection animated segment'>
						{$output}
					</div>
					<div class='inputs'></div>";
        return $output;
    }

}