<?php 

$fieldEdit = new Field($input->get->name);

$colCount = 0;
foreach ($fieldEdit->data as $key => $value) {

	$attr = $value->attributes();

	if ($colCount === 0) $output .= "<div class='row'>";
	$colCount += $attr->col;

	$ft = new FieldtypeText();
	$ft->set("name", $key);
	$ft->set("label", $key);
	$ft->set("value", $value);
	$output .= $ft->render();

	if ($colCount === 12) {
		$output .= "</div>";
		$colCount = 0;
	}

}