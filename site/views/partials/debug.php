<?php namespace Phlatson ?>
<div class="container">
	<?php




	r($models);


	$properties = [
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
		"object" => new Page("/about/")
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