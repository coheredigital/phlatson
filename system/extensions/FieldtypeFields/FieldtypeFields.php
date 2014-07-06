<?php

class FieldtypeFields extends Fieldtype
{

    protected function setup()
    {
        api('config')->styles->add($this->url . "{$this->className}.css");
        api('config')->scripts->add($this->url . "{$this->className}.js");
    }


    public function getOutput($array)
    {

        $fields = new ObjectArray();

        if( count($array) ) foreach ($array as $item){

            if (!isset($item['name'])) continue;

            $field = api("fields")->get($item['name']);
            $fields->add($field);
        }

        if( $this->object instanceof Object && count( $this->object->defaultFields ) ){
            foreach ($this->object->defaultFields as $item){
                $field = api("fields")->get($item);
                if ( $field instanceof Field ) $fields->add($field);
            }
        }

        return $fields;
    }

    public function getSave($array)
    {

        $formattedArray = [];

        // remove invalid fields
        foreach ($array as $key => $name) {
            if (!api("fields")->get($key)) unset($array[$key]);
            else{
                $formattedArray[] = ["name" => $key];
            }
        }

        return $formattedArray;
    }


    protected function renderInput()
    {

        $attributes = $this->getAttributes();

        $fields = api("fields")->all();
        $fieldsSelect = api("extensions")->get("FieldtypeSelect");

        $fieldsSelect->setOptions($fields);

        $fieldAdd = $fieldsSelect->render();

        foreach ($this->value as $field) {

            $columns = trim($field->attributes('col'));

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