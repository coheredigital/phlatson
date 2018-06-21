<?php
namespace Flatbed;
class Field extends FlatbedObject
{
    const BASE_FOLDER = 'fields/';
    const BASE_URL = 'templates/';

    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    function __construct($file = null)
    {
        parent::__construct($file);
    }

    public function fieldtype()
    {
        $fieldtype = $this->data("fieldtype");

        // if (!$fieldtype) {
        //     throw new Exceptions\FlatbedException("Fieldtype value (required) is not set for field : $this->name");
        // }
        $fieldtype = $this->api("extensions")->get($fieldtype);

        if (!$fieldtype instanceof Fieldtype) {
            throw new Exceptions\FlatbedException("Failed to retrieve field type for field : $this->name");
        }
        return $fieldtype;
    }

    public function get( string $name)
    {
    }

}
