<?php

namespace Phlatson;

/**
 * Root class that ties system together
 *      - Allow for extending of & addition to class methods
 */

class Phlatson
{

    use ApiAccess;

    protected array $apps = [];


    public function importApp(App $app)
    {
        // TODO: lazy method, needs improvement
        foreach ($app->domains as $domain) {
            $this->apps[$domain] = $app;
        }
    }


    public function execute(Request $request)
    {
        if (isset($this->apps[$request->domain])) {
            $app = $this->apps[$request->domain];
            $app->execute($request);
        }
    }

}
