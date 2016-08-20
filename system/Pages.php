<?php


class Pages extends Objects
{

    protected $rootFolder = "pages/";
    protected $singularName = "Page";

    public function __construct()
    {
        parent::__construct();
        // insert homepage reference
        $this->data['/'] = "{$this->path}data.json";
    }


    public function _render($path)
    {
        $page = $this->get($path);
        if ($page instanceof Page) {
            $this->api("page",$page);
            echo $page->template->view;
        }
        else{
            echo "Page not found! ($path)";
        }
    }

}
