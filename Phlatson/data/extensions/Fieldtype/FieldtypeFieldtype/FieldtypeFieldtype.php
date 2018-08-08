<?php
namespace Phlatson;
class FieldtypeFieldtype extends Fieldtype implements ProvidesOptionsInterface
{
    protected $page;
//    protected $objectType = "field";

    public function getOutput($name)
    {
        $fieldtype = $this->api("extensions")->get("$name");
        return $fieldtype;
    }

    public function getSave($value)
    {
        if ($value instanceof Fieldtype) {
            return $value->name;
        }
        else{
            $fieldtype = $this->api("extensions")->get($value);
            if ($fieldtype instanceof Fieldtype) return $fieldtype->name;
        }
        return null;
    }

    public function options()
    {

        $fieldtypes = $this->api("extensions")->all();
        $fieldtypes
            ->filter(["type"=>"Fieldtype"])
            ->sort("title");

        $options = [];
        foreach($fieldtypes as $fieldtype) {
            $options[$fieldtype->title] = $fieldtype->name;
        }

        return $options;
    }

}
