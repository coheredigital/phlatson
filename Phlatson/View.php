<?php

namespace Phlatson;

class View extends PhlatsonObject
{
    const BASE_FOLDER = 'views/';
    const BASE_URL = 'views/';

    function __construct(string $name)
    {
        parent::__construct($name);
        $filepath = DATA_PATH . $this::BASE_FOLDER . $name . ".php";
        // volidate view file
        if (!file_exists($filepath)) {
            throw new Exceptions\PhlatsonException("Ivalide file ($filepath) cannot be used as view");
        }
        $this->file = $filepath;
    }

    public function renderPartial(? string $url = null) : string
    {
        $file = 
        $output = "";
    }


    public function renderSelf() : string
    {
        return $this->renderViewFile($this->file);
    }


    public function renderViewFile(string $file) : string
    {

        // render template file
        ob_start();

        // give the rendered page access to the API
        extract($this->api());

        // render found file
        include($this->file);

        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }


    public function render(? string $url = null) : string
    {
        $output = "";
        if ($url) {
            $output = $this->renderPartial($url);
        } else {
            $output = $this->renderSelf();
        }
        return $output;
    }

}