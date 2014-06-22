<?php


class Extensions extends Objects
{

    protected $rootFolder = "extensions/";
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
                if ($this->has($name)) {
                    $file = $this->getFilename($name);
                    $extension = new $name($file);
                    return $extension;
                }

        }




    }

}