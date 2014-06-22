<?php

class Paths extends SimpleArray
{

    public function __construct($root)
    {
        $this->root = $root;
    }


    public static function normalizeSeparators($path)
    {
        if (DIRECTORY_SEPARATOR == '/') {
            return $path;
        }
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
        return $path;
    }


    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        if ($this->data["$name"]) {
            $value = $this->data["$name"];
        }
        if (!is_null($value)) {
            if ($value[0] == '/' || (DIRECTORY_SEPARATOR != '/' && $value[1] == ':')) {
                return $value;
            } else {
                $value = $this->root . $value;
            }
        }
        return $value;
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    public function set($name, $value)
    {
        if ($name && $value && is_array($this->data)) {
            if (isset($this->data["$name"]) && $name != "root") {
                return false;
            } // only allow root value to be overwritten when already set
            $value = $this->normalizeSeparators($value);
            $this->data[$name] = $value;
        }

    }

}
