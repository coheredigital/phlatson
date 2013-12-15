<?php


$pageEdit = $pages->get($input->get->page);

if (count($input->post)) {
	$pageEdit->save($input->post);
	// $session->redirect($input->query);
}


$form = $extensions->get("MarkupForm");
$form->setup($pageEdit);

// $settingsFieldgroup = new \Fieldgroup("settings");
// $form->addFieldgroup($settingsFieldgroup);

$templateField = $fields->get("template");
$templateField->attributes('col', 12);
// $templateField->set("value",  $pageEdit->template->name);



$form->addField($templateField);

$output = $form->render();