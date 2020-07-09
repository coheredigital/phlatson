<?php 

namespace Phlatson;


$clockwork->info($page->url(), [ 'trace' => true ]);
d($config);
// d($config->debug);

$test = $finder->getType("Page","/");

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);