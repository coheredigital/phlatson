<?php


class Pages extends Objects
{

    protected $allowRootRequest = true; // allows a "root" or null request to check for a data file in the "root"
    protected $root = "pages/";
    protected $singularName = "Page";


    public function get($query)
    {
        $query = (string) $query; // stringify $object
        $requests = explode("/", $query);

        // if first segment is equal to configured adminUrl we return an AdminPage object
        if ($requests[0] == api("config")->adminUrl) {
            return new AdminPage($query);
        } else {
            return parent::get($query);
        }
    }

}
