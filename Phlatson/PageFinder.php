<?php

namespace Phlatson;

class PageFinder
{
    // TODO: placeholder
    protected DataStorage $data;

    public function __construct(DataStorage $data)
    {
        $this->data = $data;
    }

    public function get($uri): Page
    {
        $datafile = $this->data->get($uri);
        // code...
        $page = new Page($datafile);
    }
}
