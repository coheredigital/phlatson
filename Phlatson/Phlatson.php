<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Allow for extending of & addition to class methods
 */

class Phlatson
{

    protected array $apps = [];

    public function importApp(App $app)
    {
        // TODO: lazy method, needs improvement
        foreach ($app->domains as $domain) {
            $this->apps[$domain] = $app;
        }
    }

    public function app($domain): ?App
    {

        if (!isset($this->apps[$domain])) {
            return null;
        }

        return $this->apps[$domain];

    }

    public function execute(Request $request)
    {
        if ($app = $this->app($request->domain)) {
            $app->execute($request);
        }
    }
}
