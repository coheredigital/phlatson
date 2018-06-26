<?php
namespace Phlatson;
class FieldtypeRole extends Fieldtype implements ProvidesOptionsInterface
{

    protected $objectType = "template";

    public function getOutput($name)
    {
        $template = $this->api("roles")->get($name);
        $template->parent = $this->object;
        return $template;
    }

    public function getSave($value)
    {
        if ($value instanceof Role) {
            return $value->name;
        }
        else{
            $template = $this->api("roles")->get($value);
            if ($template instanceof Role) return $template->name;
        }
        return null;
    }

    public function options()
    {

        $roles = $this->api("roles")->all();

        $options = [];
        foreach($roles as $role) {
            $options[$role->title] = $role->name;
        }

        return $options;
    }

}
