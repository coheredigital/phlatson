<?php

namespace Flatbed;

class View extends FlatbedObject
{
    const DATA_FOLDER = 'views';
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    protected $page;
    protected $file;

    function __construct($file)
    {
        if(!file_exists($file)) {
            throw new Exceptions\FlatbedException("Ivalide file ($file) cannot be used as view");
        }
        $this->file = $file;
    }


    public function render(?Page $page = null) {

        // render template file
        ob_start();

        // TODO :  add support for overriding default page with passed in value

        // give the rendered page access to the API
        extract($this->api());

        // render found file
        include($this->file);

        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }


}