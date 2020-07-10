<?php 

namespace Phlatson;

d($config);
d($finder);
d($page);

$test = $finder->getType("Page","/");

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);