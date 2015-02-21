<?php

class FieldtypeFields extends Fieldtype
{


    public function getOutput($array)
    {

        $fields = new ObjectCollection();

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



}