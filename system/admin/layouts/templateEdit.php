<?php 
$templateEdit = new Template($input->get->name);


if (count($input->post)) {
	$fieldEdit->save($input->post);
	$session->redirect($input->query);
}

$form = new \markup\EditForm($templateEdit);
$output = $form->render();