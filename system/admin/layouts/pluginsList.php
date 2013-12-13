<?php 

// $p = $plugins->get("FieldtypeText");
$pluginsList = $plugins->all();


$test = $plugins->get("FieldtypeText");
// var_dump($config->urls);

$table = $plugins->get("MarkupTable");
$table->setColumns(array(
		"Title" => "title", 
		"" => "description", 
		"Version" => "version" 
	));


foreach ($pluginsList as $item) {
	$table->addRow(array(
		"title" => $item->title,
		"description" => $item->description,
		"version" => $item->version
	));
}


$output = $table->render();