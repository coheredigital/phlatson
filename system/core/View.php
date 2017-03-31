<?php

class View extends Object
{
    protected $rootFolder = "view";
    protected $attributes = null;
    protected $requiredElements = ["fieldtype","input"];

    function __construct($file = null)
    {

        parent::__construct($file);

        $this->defaultFields = array_merge($this->defaultFields, [
            "fieldtype",
            "input"
        ]);

        $this->skippedFields = array_merge($this->skippedFields, [
            "template"
        ]);
        $this->lockedFields = [
            "template"
        ];

        $this->setUnformatted("template", "field");

    }


    public function render($page) {

        // render template file
        ob_start();

        // add page as api variable
        $this->api("page", $page);

        // give the rendered page access to the API
        extract($this->api());

        // render found file
        include($this);

        $output = ob_get_contents(); 
        ob_end_clean();
        return $output;


    }

}