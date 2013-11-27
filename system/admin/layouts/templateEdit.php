<?php 
$templateEdit = new Template($input->get->name);


if (count($input->post)) {
	$templateEdit->save($input->post);
	$session->redirect($input->query);
}

$form = new \markup\EditForm($templateEdit);
$output = $form->render();