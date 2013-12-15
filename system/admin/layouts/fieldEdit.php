<?php 

$fieldEdit = new Field($input->get->name);

if (count($input->post)) {

	$fieldEdit->save($input->post);
	$session->redirect($input->query);
}

$form = $extensions->get("MarkupForm");
$form->setup($fieldEdit);
$output = $form->render();