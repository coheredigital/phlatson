<?php


class Extensions extends Objects
{

    protected $root = "extensions/";
    protected $singularName = "extension";


    public function get($url)
    {

        if (is_object($url)) {
            $url = (string)$url;
        } // stringify $object

        if (!isset($this->data[$url]) && !$this->allowRootRequest) {
            return false;
        }
        $object = new $url();
        return $object;


    }

}