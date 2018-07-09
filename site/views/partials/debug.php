<div class="container">
	<?php

	$properties = [
		'name',
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
		"object" => $page
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