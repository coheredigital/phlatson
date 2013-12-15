<?php 
$templateEdit = new Template($input->get->name);


if (count($input->post)) {
	$templateEdit->save($input->post);
	$session->redirect($input->query);
}

$form = $extensions->get("MarkupForm");
$form->setup($templateEdit);
$output = $form->render();