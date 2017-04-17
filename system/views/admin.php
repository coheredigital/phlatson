<?php 

$layout = $views->get('layouts/default');

if ($page->messages) {
	$layout->main .= $views->get('partials/messages')->render();
}

$layout->main .= $views->render('partials/header');



echo $layout->render();