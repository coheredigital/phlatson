<?php 

namespace Phlatson;

// FIXME: This crashes
// dump($page->modified);

echo $view->render('/layouts/default', [
    "output" => $view->render("/partials/body")
]);