<?php

namespace Phlatson;

$output = $this->render("/partials/body");
$output .= $this->render("/partials/children");

echo $this->render('/layouts/default', [
    "output" => $output
]);
