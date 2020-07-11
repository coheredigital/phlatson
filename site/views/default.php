<?php 

namespace Phlatson;

$test = $finder->getType("Page","/");

$output = $view->render("/partials/body");
// $output .= $view->render("/partials/children");

echo $view->render('/layouts/default', [
    "output" => $output
]);
