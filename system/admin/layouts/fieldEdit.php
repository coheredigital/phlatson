<?php 

$fieldEdit = new Field($input->get->name);

if (count($input->post)) {
	$fieldEdit->save($input->post);
	$session->redirect($input->query);
}

$form = new \markup\EditForm($fieldEdit);
$output = $form->render();