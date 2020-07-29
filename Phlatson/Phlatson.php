<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Gives access to the internal Api
 *      - Allow for extending of & addition to class methods
 */

class Phlatson
{

    use ApiAccess;

    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->config = new Config("$path/config/data.json");
    }

}
