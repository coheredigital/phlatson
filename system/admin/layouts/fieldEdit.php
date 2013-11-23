<?php 

$fieldEdit = new Field($input->get->name);
$template = new Template('field', $config->paths->systemTemplates.'field');

// $fieldName = $input->get->name;
// $fieldEdit = $fields->$fieldName;



$colCount = 0;
$fieldsOutput = "";

$fields = $template->fields();

// var_dump($fields);
foreach ($fields as $field) {


// foreach ($fields as $key => $value) {
// 	$attr = $value->attributes();
// 	$field = new Field($value);



	if ($field instanceof Field ) {

		$ft = (string) $field->fieldtype;
		// var_dump($field->fieldtype);
		if ($ft) {


			$colCount += $field->attributes('col');
			$fieldType = new $ft();
			$fieldType->set('label', $field->label);
			$fieldType->set('name', $field->name);
			$fieldType->set('value',$pageEdit->$field);
			$fieldType->set('columns',$field->attributes('col'));
			$rowFields .= $fieldType->render();
		}
	}

	if ($colCount === 12) {
		$fieldsOutput .= "<div class='row'>{$rowFields}</div>";
		$rowFields = "";
		$colCount = 0;
	}
}
$submit = "<button form='pageEdit' type='button' class='button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";
$output = "<form id='pageEdit' action='' method='POST' role='form'>{$fieldsOutput}{$submit}</form>";