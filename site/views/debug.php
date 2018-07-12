<?php 

namespace Phlatson;

$home = new Page("/");

r($home->parent());

echo $view->render('/layouts/default', [
	"output" => $view->render("/partials/debug")
]);