<?php

namespace Phlatson;

class View extends PhlatsonObject
{
    const BASE_FOLDER = 'views/';
    const BASE_URL = 'views/';

    function __construct(string $name)
    {
        $filepath = SITE_PATH . $this::BASE_FOLDER . $name . ".php";
        // volidate view file
        if(!file_exists($filepath)) {
            throw new Exceptions\PhlatsonException("Ivalide file ($filepath) cannot be used as view");
        }
        $this->file = $filepath;
    }

    public function render(?Page $page = null) {

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

}