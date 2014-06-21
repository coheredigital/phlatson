<?php

class Template extends Object
{

    protected $fields;
    protected $rootFolder = "templates/";

    protected function getFields()
    {
        if (!$this->fields){
            $fieldsArray = $this->getUnformatted("fields");
            $fields = new FieldArray();
            foreach ($fieldsArray as $f) {
                $field = api("fields")->get($f["name"]);
                $fields->add($field);
            }
            $this->fields = $fields;
        }
        return $this->fields;
    }

    public function get($name)
    {
        switch ($name) {
            case 'fields':
                return $this->getFields();
            case 'template':
                return api("templates")->get("template");
            case 'layout':
                $layoutFile = api('config')->paths->layouts . $this->name . ".php";
                return $layoutFile;
            default:
                return parent::get($name);
        }
    }

}