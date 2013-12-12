<?php 

// $p = $plugins->get("FieldtypeText");
$pluginsList = $plugins->all();


$test = $plugins->get("FieldtypeText");
var_dump($test);

$table = $plugins->get("MarkupTable");
$table->setColumns(array(
		"Name" => "className", 
		"Version" => "version" 
	));


foreach ($pluginsList as $item) {
	$table->addRow(array(
		"className" => $item->className,
		"version" => 100
	));
}


$output = $table->render();