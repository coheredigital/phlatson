<?php 

namespace Phlatson;

$test = $finder->get("Page","/");

$output = $this->render("/partials/body");
$output .= $this->render("/partials/children");

echo $this->render('/layouts/default', [
    "output" => $output
]);
