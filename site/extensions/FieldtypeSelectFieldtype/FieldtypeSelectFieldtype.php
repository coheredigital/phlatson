<?php

class FieldtypeSelectFieldtype extends FieldtypeSelect
{


    public function getOutput($name)
    {
        $template = api("fields")->get("$name");
        return $template;
    }

    public function getSave($value)
    {
        if ($value instanceof Field) {
            $value = $value->name;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }

    public function getOptions(){


        $this->selectOptions = api("extensions")->all();
        $this->selectOptions->filter([
            "type" => "Fieldtype"
        ]);

        $output = "";

        if ($this->selectOptions instanceof ObjectArray) {
            foreach ($this->selectOptions as $object) {
                $selected = $this->value == $object->name ? "selected='selected'" : null;
                $output .= "<option {$selected} value='{$value->name}'>{$object->name}</option>";
            }
            return $output;
        } else {

            foreach ($this->selectOptions as $key => $value) {
                $selected = $this->value == $value ? "selected='selected'" : null;
                $output .= "<option {$selected} value='{$value}'>{$key}</option>";
            }
        }

        return $output;

    }


}