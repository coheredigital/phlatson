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

    private function getLayout()
    {
        $layoutFile = api('config')->paths->layouts . $this->name . ".php";
        $layoutFile = is_file($layoutFile) ? $layoutFile : null;
        return $layoutFile;
    }

    public function get($name)
    {
        switch ($name) {
            case 'fields':
                return $this->getFields();
                break;
            case 'template':
                return api("templates")->get("template");
                break;
            case 'layout':
                return $this->getLayout();
                break;
            default:
                return parent::get($name);
                break;
        }
    }

}