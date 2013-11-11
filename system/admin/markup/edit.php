<?php

	$url = str_replace(SITE_URL, "", $_GET["edit"])."/";
	$pageEdit = $pages->get($url);
	var_dump($pageEdit->template);

	$template = new Template($pageEdit->template);

	$colCount = 0;

	$output = "";



	$imageJson = "var images = '/XPages/site/content/".$pageEdit->url(false)."/images.json';";
	$scripts = "<script type='text/javascript'>$imageJson</script>";

	foreach ($template->_data as $value) {
		$attr = $value->attributes();
		$field = new Field($value);
		$ft = (string) $field->fieldtype;
		$fieldType = new $ft();

		$input = $fieldType->getInput($field->name, $pageEdit->$value);

		if (!$colCount) $output . "<div class='row'>";
		$colCount += $attr->col;

		$output .= "<div class='col-md-{$attr->col}'>
						<div class='panel panel-default'>
							<div class='panel-heading'>{$field->label}</div>
							<div class='panel-body'>
								{$input}
							</div>
						</div>
					</div>";
		if ($colCount == 12) {
			$colCount = 0;
			$output . "</div>";
		}

	}

	$output = "$scripts<form action=' role='form'>{$output}</form>";

	echo $output;

