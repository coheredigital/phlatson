<?php

class FieldtypeTemplate extends FieldtypeSelect
{

    protected $objectType = "template";


    public function getOutput($name)
    {
        $template = api("templates")->get($name);
        if ($this->object){
            $template->setReference($this->object);
        }
        return $template;
    }

    public function getSave($value)
    {
        if ( $value instanceof Template) {
            $value = $value->name;
        }
        $value = "$value"; // stringify for certainty :)
        return $value;
    }

    protected function setup(){
        $this->label = "Template";
        $this->columns = 6;
        $this->attribute("name", "template");
    }

    public function setObject( Object $page ){
        $this->object = $page;
        $this->setAllowedTemplates();
        $this->value = $this->object->template->name;
    }

    protected function setAllowedTemplates(){
        $selectOptions = array();

        $templates = api("templates")->all();
        foreach ($templates as $t) {
            $selectOptions["$t->label"] = "$t->name";
        }
        $this->setOptions($selectOptions);
    }

}