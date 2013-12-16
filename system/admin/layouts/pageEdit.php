<?php


$edit = $pages->get($input->get->page);

if (count($input->post)) {
	$edit->save($input->post);
	// $session->redirect($input->query);
}


$form = $extensions->get("MarkupEditForm");
$editFields = $edit->template->fields;
foreach ($editFields as $field) {
	$input = $field->type;


	$input->label = $field->label;
	$input->columns = $field->attributes('col') ? (int) $field->attributes('col') : 12;
	$input->value = $edit->getUnformatted($field->name);
	$input->attribute("name",$field->name);
	$form->add($input);
}

$templateField = $extensions->get("FieldtypeText");
$templateField->label = "Template";
$templateField->columns = 12;
$templateField->value = $edit->template->name;

$form->add($templateField);

$submitButtons = $extensions->get("FieldtypeFormActions");
$form->add($submitButtons);
$output = $form->render();