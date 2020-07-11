<?php

namespace Phlatson;

abstract class DataObject extends BaseObject
{

    protected JsonObject        $data;
    protected array             $formattedData  = [];
    protected FieldCollection   $fields;
    protected Template          $template;


    public function __construct($path = null)
    {
        if (is_null($path)) {
            return;
        }
        $path = '/' . trim($path, '/') . '/';

        $classname = $this->classname();

        $jsonData = $this->api('finder')->getData($classname, $path);
        $this->setData($jsonData);

        if ($template = $this->data->get('template')) {
            $this->template = $this->api('finder')->getType("Template", $template);
        }
        

    }

    public function setData(JsonObject $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function template()
    {
        if ($name = $this->data('template')) {
            return new Template($name);
        }
    }

    public function exists(): bool
    {
        return file_exists($this->file);
    }

    /**
     * Retreive raw data from the data object
     *
     * @param string $key
     * @return void
     */
    public function data(string $key)
    {
        return $this->data->get($key);
    }

    public function get(string $key)
    {
        $value = null;
        switch ($key) {
            case 'template':
                $value = $this->template();
                break;
            default:
                $value = $this->data->get($key);

                if ($this->data->get($key)) {
                    $field = $this->api('finder')->getType("Field", $key);
                    $fieldtype = $field->type();
                }

                $value = $this->data->get($key);
                break;
        }

        
        return $value ?: parent::get($key);

    }
}
