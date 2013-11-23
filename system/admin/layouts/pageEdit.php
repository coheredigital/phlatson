
<?php
	$pageEdit = $pages->get($input->get->page);

	if (count($input->post)) {
		$pageEdit->save($input->post);
		$session->redirect($input->query);
	}

	$template = $pageEdit->template;

	$colCount = 0;
	$fieldsOutput = "";

	$fields = $template->fields();


	foreach ($fields as $field) {


	// foreach ($fields as $key => $value) {
	// 	$attr = $value->attributes();
	// 	$field = new Field($value);



		if ($field instanceof Field ) {

			$ft = (string) $field->fieldtype;
			// var_dump($field->fieldtype);
			if ($ft) {


				$colCount += $field->attributes('col');
				$fieldType = new $ft();
				$fieldType->set('label', $field->label);
				$fieldType->set('name', $field->name);
				$fieldType->set('value',$pageEdit->$field);
				$fieldType->set('columns',$field->attributes('col'));
				$rowFields .= $fieldType->render();
			}
		}

		if ($colCount === 12) {
			$fieldsOutput .= "<div class='row'>{$rowFields}</div>";
			$rowFields = "";
			$colCount = 0;
		}
	}


	$submit = "<button form='pageEdit' type='submit' class='button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";
	// $submit = "<div class='row'><div class='col-12'><input class='button button-save pull-right' type='submit' value='Save'></div></div>";
	$output = "<form id='pageEdit' action='' method='POST' role='form'>{$fieldsOutput}{$submit}</form>";