<?php


class Pages extends Objects
{

    protected $rootFolder = "pages/";
    protected $singularName = "Page";

    public function get($query)
    {


        // if first segment is equal to configured adminUrl we return an AdminPage object
        if ( $this->isAdminRequest($query)) {
            $this->singularName = "AdminPage";
        }
        else{
            $this->singularName = "Page";
        }

        return parent::get($query);
    }

    protected function isAdminRequest($query){
        $requests = explode("/", $query);
        $requestRoot = normalizeDirectory($requests[0]);
        $adminUrl = normalizeDirectory(api("config")->adminUrl);

        return $requestRoot == $adminUrl;
    }

}
