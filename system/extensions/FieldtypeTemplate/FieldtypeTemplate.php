<?php
namespace Flatbed;
class FieldtypeTemplate extends Fieldtype implements ProvidesOptionsInterface
{

    public function output($name)
    {
        $template = $this->api("templates")->get($name);
        return $template;
    }

    public function input($value)
    {
        if ($value instanceof Template) {
            return $value->name;
        }
        else{
            $template = $this->api("templates")->get($value);
            if ($template instanceof Template) return $template->name;
        }
        return null;
    }

    public function options()
    {

        $inputs = $this->api("templates")->all();
        $options = [];
        foreach($inputs as $fieldtype) {
            $options[$fieldtype->title] = $fieldtype->name;
        }

        return $options;
    }

}
