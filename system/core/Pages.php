<?php


class Pages extends Objects
{

    protected $rootFolder = "pages/";
    protected $singularName = "Page";

    public function get($query)
    {
        $requests = explode("/", $query);
        $requestRoot = normalizeDirectory($requests[0]);
        $adminUrl = normalizeDirectory(api("config")->adminUrl);
        // if first segment is equal to configured adminUrl we return an AdminPage object
        if ( $requestRoot == $adminUrl) {
            $this->singularName = "AdminPage";
        }
        else{
            $this->singularName = "Page";
        }

        return parent::get($query);
    }

}
