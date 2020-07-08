<?php 

namespace Phlatson;

// FIXME: This crashes
// \Kint::dump($request);
// \Kint::dump($finder);
$clockwork->info($page->url(), [ 'trace' => true ]);
// d($page);


$test = $finder->getType("Page","/");

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);