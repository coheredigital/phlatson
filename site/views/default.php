<?php 

namespace Phlatson;

// FIXME: This crashes
dump($page);

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);