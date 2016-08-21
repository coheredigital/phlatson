<?php

class FieldtypeTemplate extends Fieldtype
{

    protected $objectType = "template";

    public function getOutput($name)
    {
        $template = $this->api("templates")->get($name);
        $template->parent = $this->object;
        return $template;
    }

    public function getSave($value)
    {
        if ($value instanceof Template) {
            return $value->name;
        }
        else{
            $template = $this->api("templates")->get($value);
            if ($template instanceof Template) return $template->name;
        }
        return null;
    }

    protected function setup()
    {
        $this->label = "Template";
        $this->columns = 6;
        $this->attribute("name", $this->field->name);
    }

    protected function setAllowedTemplates()
    {
        $selectOptions = array();

        $templates = $this->api("templates")->all();
        foreach ($templates as $t) {
            $selectOptions["$t->label"] = "$t->name";
        }
        $this->setOptions($selectOptions);
    }
    public function options()
    {

        $inputs = $this->api("templates")->all();
        // $inputs
        //     ->filter(["type"=>"Input"])
        //     ->sort("title");

        $options = [];
        foreach($inputs as $fieldtype) {
            $options[$fieldtype->title] = $fieldtype->name;
        }

        return $options;
    }

    // protected function renderInput()
    // {
    //     $this->attribute("type", "text");

    //     if ($this->value) {
    //         $this->attribute("value", $this->value->name);
    //     }

    //     $attributes = $this->getAttributes();
    //     $output = "<input {$attributes}>";
    //     return $output;
    // }

}