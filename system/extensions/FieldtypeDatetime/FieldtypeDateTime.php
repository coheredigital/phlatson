<?php

class FieldtypeDateTime extends Fieldtype
{

    public function getOutput($value)
    {
        if(is_int($value)){
            $value = date("c", $value);
        }
        $datetime = new FlatbedDateTime($value);

        return $datetime;
    }

    public function getSave($value)
    {
        if(is_int($value)){
            $value = date("c", $value);
        }
        $datetime = new FlatbedDateTime($value);
        $value = (int) $datetime->format("U");
        return $value;
    }

    protected function renderInput()
    {


        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value);
        }

        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }


}