<?php 

namespace Phlatson;

// FIXME: This crashes
dump($request);
dump($finder);
dump($page);


$test = $finder->getType("Page","/");
dump($test->modified);

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);