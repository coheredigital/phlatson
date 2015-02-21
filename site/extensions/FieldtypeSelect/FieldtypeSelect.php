<?php
/**
 * Created by PhpStorm.
 * User: aspruijt
 * Date: 22/08/14
 * Time: 12:48 PM
 */

class FieldtypeSelect extends Fieldtype{

    public $options = [];


    protected function getConfigFields(){

        $fields = new ObjectCollection();

        $field = api("extensions")->get("FieldtypeTextarea");
        $field->label = "Options";
    }

    public function get($name){
        switch ($name) {
            case "options":
                return $this->getOptions();
            default:
                return parent::get($name);
        }
    }

    protected function getOptions(){
        return [];
    }

} 