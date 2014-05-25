<?php


class Extensions extends Objects
{

    protected $root = "extensions/";
    protected $singularName = "extension";


    protected function fieldtypes(){
        $array = $this->filter(array(
            "type" => "Fieldtype"
        ));
        return $array;
    }

    public function get($name)
    {

        switch ($name){
            case 'fieldtypes':
                return $this->fieldtypes();
            default:
                if (!isset($this->data[$name]) && !$this->allowRootRequest) {
                    return false;
                }
                $object = new $name();
                return $object;
        }




    }

}