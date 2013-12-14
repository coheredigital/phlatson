<?php 

// $p = $plugins->get("FieldtypeText");
$extensionsList = $extensions->all();


$test = $extensions->get("FieldtypeText");
// var_dump($config->urls);

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