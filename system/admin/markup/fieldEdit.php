<?php 

$fieldEdit = new Field($input->get->name);
foreach ($fieldEdit->data as $key => $value) {


	$ft = new FieldtypeText();
	$ft->set("name", $key);
	$ft->set("label", $key);
	$ft->set("value", $value);
	$output .= $ft->render();

}