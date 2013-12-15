<?php 
$extensionsList = $extensions->all();

$table = $extensions->get("MarkupTable");
$table->setColumns(array(
		"Title" => "title", 
		"" => "description", 
		"Version" => "version" 
	));

foreach ($extensionsList as $item) {
	$table->addRow(array(
		"title" => $item->title,
		"description" => $item->description,
		"version" => $item->version
	));
}

$output = $table->render();