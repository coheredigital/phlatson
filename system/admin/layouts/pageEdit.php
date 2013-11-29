<?php


$pageEdit = $pages->get($input->get->page);

if (count($input->post)) {
	$pageEdit->save($input->post);
	// $session->redirect($input->query);
}


$form = new \markup\EditForm($pageEdit);

$settingsFieldgroup = new \Fieldgroup("settings");
$form->addFieldgroup($settingsFieldgroup);

$templateField = $fields->get("template");
$templateField->attributes('col', 12);

$form->addField($templateField);

$output = $form->render();