<?php

namespace Phlatson;

class View extends BaseObject
{
    
    const BASE_FOLDER = 'views/';
    const BASE_URL = 'views/';

    function __construct(string $name)
    {

        $filepath = $this->rootPath() . $name . ".php";
        // validate view file
        if (!file_exists($filepath)) {
            throw new \Exception("Invalid file ($filepath) cannot be used as view");
        }
        $this->file = $filepath;
    }

    public function file() : string
    {
        return $this->file;
    }

    public function name() : string
    {
        return pathinfo($this->file())['filename'];
    }

    public function renderPartial(? string $url, array $data = []) : string
    {
        $url = trim($url, "/");
        $file = "{$this->path()}{$url}.php";
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
            throw new \Exception("View does not exist: $file");
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