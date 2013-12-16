<?php 

$edit = new Field($input->get->name);

if (count($input->post)) {
	$edit->save($input->post);
	// $session->redirect($input->query);
}


$form = $extensions->get("MarkupEditForm");
$editFields = $edit->template->fields;
foreach ($editFields as $field) {
	$input = $field->fieldtype;
	$input->label = $field->label;
	$input->columns = $field->attributes('col') ? (int) $field->attributes('col') : 12;
	$input->value = $edit->get($field->name);
	$input->attribute("name",$field->name);
	$form->add($input);
}
$submitButtons = $extensions->get("FieldtypeFormActions");
$form->add($submitButtons);
$output = $form->render();