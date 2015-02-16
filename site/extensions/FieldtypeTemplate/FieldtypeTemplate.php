<?php

class FieldtypeTemplate extends Fieldtype
{

    protected $objectType = "template";

    public function getOutput($name)
    {
        $template = app("templates")->get($name);
        $template->master = $this->object;
        return $template;
    }

    public function getSave($value)
    {
        if ($value instanceof Template) {
            return $value->name;
        }
        else{
            $template = app("templates")->get($value);
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

        $templates = app("templates")->all();
        foreach ($templates as $t) {
            $selectOptions["$t->label"] = "$t->name";
        }
        $this->setOptions($selectOptions);
    }


    protected function renderInput()
    {
        $this->attribute("type", "text");

        if ($this->value) {
            $this->attribute("value", $this->value->name);
        }

        $attributes = $this->getAttributes();
        $output = "<input {$attributes}>";
        return $output;
    }

}