<?php

class View extends Flatbed
{
    const DATA_FOLDER = 'views';
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    protected $file;

    function __construct($file)
    {
        if(!file_exists($file)) {
            throw new FlatbedException("Ivalide file ($file) cannot be used as view");
        }
        $this->file = $file;
    }


    public function render($page) {

        // render template file
        ob_start();

        // add page as api variable
        $this->api("page", $page);

        // give the rendered page access to the API
        extract($this->api());

        // render found file
        include($this->file);

        $output = ob_get_contents();
        ob_end_clean();
        return $output;


    }

}
