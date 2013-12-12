<?php 

// $p = $plugins->get("FieldtypeText");
$pluginsList = $plugins->all();

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