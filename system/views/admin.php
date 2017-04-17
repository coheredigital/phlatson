<?php 

$config->styles->add("/system/views/styles/admin.css");
$config->styles->add("https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css");

$config->scripts->add("{$this->url}scripts/jquery-sortable.js");
$config->scripts->add("{$this->url}scripts/hashtabber/hashTabber.js");
$config->scripts->add("{$this->url}scripts/main.js");
$config->scripts->prepend("{$this->url}scripts/jquery-1.11.1.min.js");

$layout = $views->get('layouts/default');

if ($page->messages) {
	$layout->main .= $views->get('partials/messages')->render();
}

$layout->main .= $views->render('partials/header');


if ($request->segment(1) == "fields") {
	if ($request->segment(2)) {
		$layout->main .= $views->render('partials/edit-field');
	}
	else {
		$layout->main .= $views->render('partials/list-fields');
	}
}

echo $layout->render();