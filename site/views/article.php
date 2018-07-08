<?php 

namespace Phlatson;

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);