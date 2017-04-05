<?php

// placeholder for a restructuring idea I am considering


abstract class DataContainer extends Flatbed implements JsonSerializable
{


    public $name;

    protected $path;
    protected $configPath = null;
    protected $_file;
    protected $modified;
    protected $rootFolder;


    protected $data = array();


    protected function getFormatted($name)
    {

        // get raw value
        $value = $this->getUnformatted($name);

        // get the field object matching the passed "$name"

        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; // TODO, why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed, if it is I should note the reason here

        if ($field instanceof Field ) {
            $fieldtype = $field->fieldtype;
            $fieldtype->object = $this;
            if ($fieldtype instanceof Fieldtype) {
                $value = $fieldtype->getOutput($value);
            }
        }


        return $value;
    }


    protected function setFormatted($name, $value)
    {

        // get the field object matching the passed "$name"

        $field = $this->api("fields") ? $this->api("fields")->get($name) : false; // TODO, why am I check if the $this->api("fields") instance exist yet, this shouldn't be needed

        if ($field instanceof Field ) {
            $fieldtype = $field->fieldtype;
            $fieldtype->object = $this;

            if ($fieldtype instanceof Fieldtype) {
                $value = $fieldtype->getSave($value);
            }
        }

        $this->setUnformatted($name, $value);
    }


    /**
     * set value directly to $this->data[$name]
     * skips validation of passed value
     * should not generally be used on public facing API
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function setUnformatted($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * get value directly to $this->data[$name]
     * skips formatting of passed value
     * should not generally be used on public facing API
     *
     * @param  string $name
     * @return mixed
     */
    protected function getUnformatted($name)
    {
        $value = $this->data[$name];
        return $value;
    }


    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }


    public function get($name)
    {
        switch ($name) {
            case 'className':
                return get_class($this);
            default:
                return $this->getFormatted($name);
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        switch ($name) {
            case "name":
                $name = $this->api("sanitizer")->name($value);
                $this->name = $name;
        }
        $this->setFormatted($name, $value);
        return $this;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }


    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __toString()
    {
        return $this->className;
    }

    public function jsonSerialize() {
        return $this->data;
    }

}
