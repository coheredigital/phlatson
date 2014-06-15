<?php

class FieldtypeTemplate extends FieldtypeObject
{
    protected $page;
    protected $objectType = "template";

    protected function setup(){
        $this->label = "Template";
        $this->columns = 6;
        $this->attribute("name", "template");
    }

    public function setPage(Page $page){
        $this->page = $page;
        $this->setAllowedTemplates();
        $this->value = $this->page->template->name;

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