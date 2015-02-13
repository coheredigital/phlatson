<?php


class Pages extends Objects
{

    protected $rootFolder = "pages/";
    protected $singularName = "Page";


    public function _render($path)
    {
        $page = $this->get($path);
        if ($page instanceof Page) {
            extract(registry()); // get access to api variables for rendered layout
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
        $adminUrl = normalizeDirectory(registry("config")->adminUrl);

        return $requestRoot == $adminUrl;
    }



}
