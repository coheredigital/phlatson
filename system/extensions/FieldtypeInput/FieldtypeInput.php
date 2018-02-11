<?php
namespace Flatbed;
class FieldtypeInput extends Fieldtype implements ProvidesOptionsInterface
{
    protected $page;
    protected $objectType = "field";

    public function output($name)
    {
        $field = $this->api("extensions")->get("$name");
        return $field;
    }

    public function getSave($value)
    {
        if ($value instanceof Input) {
            return $value->name;
        }
        else{
            $input = $this->api("extensions")->get($value);
            if ($input instanceof Input) return $input->name;
        }
        return null;
    }

    public function options()
    {

        $inputs = $this->api("extensions")->all();
        $inputs
            ->filter(["type"=>"Input"])
            ->sort("title");

        $options = [];
        foreach($inputs as $fieldtype) {
            $options[$fieldtype->title] = $fieldtype->name;
        }

        return $options;
    }

}
