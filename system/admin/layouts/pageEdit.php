<?php
	$pageEdit = $pages->get($input->get->page);

	if (count($input->post)) {
		foreach ($input->post as $key => $value) {			
			if ($key != "content" && $key != "published") {
				$pageEdit->$key = $input->post->$key;
			}
			$pageEdit->save();
		}
	}

	$template = $pageEdit->template;

	$colCount = 0;
	$output = "";

	$fields = $template->fields();


	foreach ($fields as $key => $value) {
		$attr = $value->attributes();
		$field = new Field($value);

		if ($field instanceof Field ) {

			$ft = (string) $field->fieldtype;
			if ($ft) {


				if ($colCount === 0) $output .= "<div class='row'>";
				$colCount += $attr->col;

				$fieldType = new $ft();
				$fieldType->set('label',$field->label);
				$fieldType->set('name',$field->name);
				$fieldType->set('value',$pageEdit->$value);
				$fieldType->set('columns',$attr->col);
				$output .= $fieldType->render();

				if ($colCount === 12) {
					$output .= "</div>";
					$colCount = 0;
				}
			}


		}


	}
	if ($colCount === 12 && $colCount === 0) {
		$output .= "</div>";
	}

	$submit = "<button class='button button-save pull-right' type='submit'><i class='icon icon-floppy-o'></i></button>";
	$output = "<form action='' method='POST' role='form'>{$output}{$submit} </form>";

	?>

