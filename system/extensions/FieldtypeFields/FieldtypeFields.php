<?php

class FieldtypeFields extends Fieldtype implements ProvidesOptions
{


    public function getOutput($array)
    {

        $fields = new ObjectCollection();

        if(count($array)) foreach ($array as $item) {
            if(isset($item['name']) && $field = $this->api("fields")->get($item['name'])) {
                $fields->append($field);
            }
        }


        if($this->object instanceof Object) $defaultFields = $this->object->parent->defaultFields;

        if (count($defaultFields)) {
            foreach ($defaultFields as $item) {
                $field = $this->api("fields")->get($item);
                if ($field instanceof Field) {
                    $fields->append($field);
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
            if (!$this->api("fields")->get($key)) {
                unset($array[$key]);
            } else {
                $formattedArray[] = ["name" => $key];
            }
        }

        return $formattedArray;
    }

    public function options(){

        $options = [];
        $fields = $this->api("fields")->all();

        foreach($fields as $field){
            $options["$field->title"] = $field->name;


        }

        return $options;

    }

}