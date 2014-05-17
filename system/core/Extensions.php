<?php


class Extensions extends Objects
{

    protected $root = "extensions/";
    protected $singularName = "extension";


    public function get($query)
    {

        if (is_object($query)) {
            $query = (string)$query;
        } // stringify $object

        if (!isset($this->data[$query]) && !$this->allowRootRequest) {
            return false;
        }
        $object = new $query();
        return $object;


    }

}