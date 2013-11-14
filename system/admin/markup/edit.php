<?php
	$pageEdit = $pages->get($input->get->page);

	if (count($input->post)) {
		foreach ($input->post as $key => $value) {
			// var_dump("KEY: $key => VALUE: $value");
			
			if ($key != "content" && $key != "published") {
				$pageEdit->$key = $input->post->$key;
			}
			$pageEdit->save();
		}
		
	}


	$template = new Template($pageEdit->template);

	$colCount = 0;
	$output = "";


	
	foreach ($template->field as $key => $value) {
		$attr = $value->attributes();
		$field = new Field($value);


		if ($field instanceof Field ) {

			$ft = (string) $field->fieldtype;
			if ($ft) {
				$fieldType = new $ft();
				$input = $fieldType->getInput($field->name, $pageEdit->$value);

				if (!$colCount) $output .= "<div class='row'>";
				$colCount += $attr->col;

				$output .= "<div class='col col-{$attr->col}'>
								<div class='field-item'>
									<div class='field-heading'>{$field->label}</div>
									<div class='field-content'>
										{$input}
									</div>
								</div>
							</div>";
				if ($colCount == 12) {
					$output .= "</div>";
					$colCount = 0;
				}
			}



		}


	}

	$submit = "<input class='button button-save pull-right' type='submit' value='save'>";

	$output = "<form action='' method='POST' role='form'>{$output}{$submit}</form>";

	echo $output;

