<?php 

$field = new Field($input->get->name);
$form = new \markup\EditForm($field);
$output = $form->render();