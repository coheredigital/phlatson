<?php

class Template extends Object
{

    protected $dataFolder = "templates/";

    public function fields($fieldArray = null)
    {

        $fieldsArray = $this->find("fields");


        $fields = new FieldArray();
        foreach ($fieldsArray as $f) {
            $field = new Field($f->name);
            // $attr = $f->attributes();

            // foreach ($attr as $key => $value) {
            //     $field->attributes($key, $value);
            // }

            $fields->add($field);

        }

        if (is_array($fieldArray)) {
            $fields->import($fieldArray);
        }

        return $fields;
    }





    private function getLayout()
    {
        $layoutFile = $this->api('config')->paths->layouts . $this->name . ".php";
        $layoutFile = is_file($layoutFile) ? $layoutFile : null;
        return $layoutFile;
    }

    public function get($string)
    {
        switch ($string) {
            case 'fields':
                return $this->fields();
                break;
            default:
                return parent::get($string);
                break;
            case 'template':
                return $this->getTemplate("template");
                break;
            case 'layout':
                return $this->getLayout();
                break;
            default:
                return parent::get($string);
                break;
        }
    }

}