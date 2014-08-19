<?php


class Pages extends Objects
{

    protected $rootFolder = "pages/";
    protected $singularName = "Page";

    public function get($query)
    {


        // if first segment is equal to configured adminUrl we return an AdminPage object
        if ($this->isAdminRequest($query)) {
            $this->singularName = "AdminPage";
        } else {
            $this->singularName = "Page";
        }

        return parent::get($query);
    }

    public function render($path)
    {
        $page = $this->get($path);
        if ($page instanceof Page) {
            extract(api()); // get access to api variables for rendered layout
            include $page->template->layout;
        }
        else{
            echo "Page not found! ($path)";
        }
    }

    protected function isAdminRequest($query)
    {
        $requests = explode("/", $query);
        $requestRoot = normalizeDirectory($requests[0]);
        $adminUrl = normalizeDirectory(api("config")->adminUrl);

        return $requestRoot == $adminUrl;
    }



}
