<?php


class Pages extends Objects
{


    protected $rootFolder = "pages";
    protected $singularName = "Page";
    const SINGULAR_CLASSNAME = 'Page';
    
    // public function _render($path)
    // {
    //     $page = $this->get($path);
    //     if ($page instanceof Page) {
    //         echo $page->render();
    //     }
    //     else{
    //         echo "Page not found! ($path)";
    //     }
    // }

}
