
<?php
	$pageEdit = $pages->get($input->get->page);

	if (count($input->post)) {
		$pageEdit->save($input->post);
		$session->redirect($input->query);
	}


	$form = new \markup\EditForm($pageEdit);
	$output = $form->render();
	

