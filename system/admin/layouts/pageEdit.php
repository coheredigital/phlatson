<?php

$edit = $pages->get($input->get->page);
if (count($input->post)) {
	$edit->save($input->post);
	$session->redirect($input->query);
}
$output = $page->render();