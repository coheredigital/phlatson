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

                $field = api("fields")->get($item['name']);
                $fields->add($field);
            }
        }

        if ($this->object instanceof Object && count($this->object->defaultFields)) {
            foreach ($this->object->defaultFields as $item) {
                $field = api("fields")->get($item);
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
            if (!api("fields")->get($key)) {
                unset($array[$key]);
            } else {
                $formattedArray[] = ["name" => $key];
            }
        }

        return $formattedArray;
    }

}