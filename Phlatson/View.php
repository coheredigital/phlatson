<?php

namespace Phlatson;

class View extends BaseObject
{
    const BASE_FOLDER = 'views/';
    const BASE_URL = 'views/';

    function __construct(string $name)
    {
        parent::__construct($name);
        $filepath = DATA_PATH . $this::BASE_FOLDER . $name . ".php";
        // volidate view file
        if (!file_exists($filepath)) {
            throw new Exceptions\PhlatsonException("Ivalid file ($filepath) cannot be used as view");
        }
        $this->file = $filepath;
    }

    protected function file()
    {
        return $this->file;
    }

    public function renderPartial(? string $url, array $data = []) : string
    {
        $url = trim($url, "/");
        $file = "{$this->path}{$url}.php";
        $output = "";
        $output = $this->renderViewFile($file, $data);
        return $output;
    }

    public function renderSelf() : string
    {
        return $this->renderViewFile($this->file);
    }

    public function renderViewFile(string $file, array $data = []) : string
    {

        if (!file_exists($file)) {
            throw new Exceptions\PhlatsonException("View does not exist: $file");
        }

        // render template file
        ob_start();
        // extract $data array to varyables
        extract($data);

        // give the rendered page access to the API
        extract($this->api());

        // render found file
        include($file);

        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }


    public function render(? string $url = null, array $data = []) : string
    {
        $output = "";
        if ($url) {
            $output = $this->renderPartial($url, $data);
        } else {
            $output = $this->renderSelf();
        }
        return $output;
    }

}