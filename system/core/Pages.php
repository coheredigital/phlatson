<?php


class Pages extends Objects
{

    protected $allowRootRequest = true; // allows a "root" or null request to check for a data file in the "root"
    protected $root = "pages/";
    protected $singularName = "Page";


    public function get($name)
    {
        if (is_object($name)) {
            $name = (string)$name;
        } // stringify $object
        $requests = explode("/", $name);

        // if first segment is equal to configured adminUrl we return an AdminPage object
        if ($requests[0] == api("config")->adminUrl) {
            return new AdminPage($name);
        } else {
            return parent::get($name);
        }
    }


}
