<?php

	$url = str_replace(SITE_URL, "", $_GET["edit"])."/";
	$pageEdit = $pages->get($url);

	$pageTemplate = new Template($pageEdit->template);

 ?>
<form action="" role="form">

	
	<?php 

	$rowCount = 0;
	foreach ($pageTemplate->_data as $key => $value): 

		$field = new Field($key);

		?>
		
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $field->label ?></div>
		<div class="panel-body">
			<input class="form-control" type="text" name="title" id="" value="<?php echo $pageEdit->$key ?>">
		</div>
	</div>
	<?php endforeach ?>


</form>
