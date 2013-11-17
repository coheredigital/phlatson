<?php 
$templateName = $input->get->name;
$templateEdit = $templates->get($templateName);

$colCount = 0;


// name field

$ft = new FieldtypeText();
$ft->set("name", "name");
$ft->set("label", "Name");
$ft->set("value", $templateEdit->name);
$output .= $ft->render();



// foreach ($templateEdit->data as $key => $value) {

// 	$attr = $value->attributes();

// 	if ($colCount === 0) $output .= "<div class='row'>";
// 	$colCount += $attr->col;

// 	$ft = new FieldtypeText();
// 	$ft->set("name", $key);
// 	$ft->set("label", $key);
// 	$ft->set("value", $value);
// 	$output .= $ft->render();

// 	if ($colCount === 12) {
// 		$output .= "</div>";
// 		$colCount = 0;
// 	}

// }