<?php namespace Phlatson ?>
<div class="container">
	<?php

	$properties = [
		'root',
		'name',
		'rootUrl',
		'url',
		'rootFolder',
		'folder',
		'rootPath',
		'path',
		'file',
		'filename',
	];

    echo $view->render('/partials/debug/object-table', [
		"properties" => $properties,
		"object" => new Page("/")
	]);
	echo $view->render('/partials/debug/object-table', [
		"properties" => $properties,
		"object" => $page->template
	]);
	echo $view->render('/partials/debug/object-table', [
		"properties" => $properties,
		"object" => $page->template->view
	]);

    ?>

</div>