<?php 

namespace Phlatson;

// FIXME: This crashes
dump($request);
dump($finder);
dump($page);

// $finder->addPathMapping("Page","");

// $test = $finder->get("Page::/");

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);