<?php
namespace Phlatson;
class Config extends PhlatsonObject
{


    public function __construct()
    {


        // load site config
        $this->file = SITE_PATH . "config" . DIRECTORY_SEPARATOR . "site.json";

        // merge site config with default data
        $this->data = array_merge($this->data, $this->getData());
    

    }

}