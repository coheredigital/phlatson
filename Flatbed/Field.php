<?php
namespace Flatbed;
class Field extends Object
{
    const DATA_FOLDER = 'fields';
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    const SYSTEM_ROOT = SYSTEM_PATH . "fields" . DIRECTORY_SEPARATOR;
    const SITE_ROOT = SYSTEM_PATH . "fields" . DIRECTORY_SEPARATOR;

    function __construct($file = null)
    {

        parent::__construct($file);

        // $this->defaultFields = array_merge($this->defaultFields, [
        //     "fieldtype",
        //     "input"
        // ]);

        // $this->skippedFields = array_merge($this->skippedFields, [
        //     "template"
        // ]);

        // $this->lockedFields = [
        //     "template"
        // ];


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
        switch ($name) {
            // get fieldtype needs manual handling to avoid an infinite loop
            case 'template':
                return $this->api('templates')->get('field');
            case 'fieldtype':
                return $this->fieldtype();
            default:
                return parent::get($name);
        }

    }

}
