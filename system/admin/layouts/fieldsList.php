<?php 

$fieldsList = $fields->all();

$table = $extensions->get("MarkupTable");
$table->setColumns(array(
		"Name" => "name", 
		"Label" => "label",
		"fieldtype" => "fieldtype",
	));

foreach ($fieldsList as $item) {
	$table->addRow(array(
		"name" => "<a href='{$adminUrl}fields/edit/?name={$item->name}' >{$item->name}</a>",
		"label" => $item->label,
		"fieldtype" => $item->fieldtype,
	));
}
$output = $table->render();