<?php 

namespace Phlatson;

// d($config);
// d($finder);
// d($page);

$test = $finder->getType("Page","/");

$output = $view->render("/partials/body");
// $output .= $view->render("/partials/children");

echo $view->render('/layouts/default', [
    "output" => $output
]);
