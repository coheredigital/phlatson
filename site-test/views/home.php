<?php 

namespace Phlatson;


$output = "";
$output = $view->render("/partials/home/body");


echo $view->render('/layouts/home', [
    "output" => $output
]);