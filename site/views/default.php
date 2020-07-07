<?php 

namespace Phlatson;

// FIXME: This crashes
// \Kint::dump($request);
// \Kint::dump($finder);
// \Kint::dump($page);


$test = $finder->getType("Page","/");

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);