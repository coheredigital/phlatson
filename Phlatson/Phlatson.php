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
    private Config $config;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->config = new Config(__DIR__ . "/data/config/data.json");
        $siteConfig = new Config("$path/config/data.json");
        $this->config->merge($siteConfig);
    }
}
