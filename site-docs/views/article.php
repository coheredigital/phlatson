<?php 

namespace Phlatson;

echo $this->render('/layouts/default', [
    "output" => $this->render("/partials/body")
]);