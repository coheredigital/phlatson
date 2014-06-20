<?php


class Pages extends Objects
{

    protected $rootFolder = "pages/";
    protected $singularName = "Page";

    public function get($query)
    {
        $requests = explode("/", $query);
        // if first segment is equal to configured adminUrl we return an AdminPage object
        if ($requests[0] == api("config")->adminUrl) {
            $this->singularName = "AdminPage";
        }
        else{
            $this->singularName = "Page";
        }

        return parent::get($query);
    }

}
