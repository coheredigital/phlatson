<?php 

// $p = $plugins->get("FieldtypeText");
$pluginsList = $plugins->all();


$test = $plugins->get("FieldtypeText");
// var_dump($config->urls);

$table = $plugins->get("MarkupTable");
$table->setColumns(array(
		"Title" => "title", 
		"Name" => "className", 
		"Version" => "version" 
	));


foreach ($pluginsList as $item) {
	$table->addRow(array(
		"title" => $item->title,
		"className" => $item->className,
		"version" => $item->version
	));
}


$output = $table->render();