<?php

	$url = str_replace(SITE_URL, "", $_GET["edit"])."/";
	$pageEdit = $pages->get($url);

	$pageTemplate = new Template($pageEdit->template);

 ?>
<form action="" role="form">

	
	<?php 

	$colCount = 0;
	foreach ($pageTemplate->_data as $key => $value): 
		$attr = $value->attributes();
		

		$field = new Field($key);

		?>
		
	<?php if (!$colCount): ?>
		<div class="row">
	<?php endif ?>
	<?php 
		$colCount = $colCount+$attr->col;
	 ?>
	
	<div class="col-md-<?php echo $attr->col ?>">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $field->label ?></div>
			<div class="panel-body">
				<input class="form-control" type="text" name="title" id="" value="<?php echo $pageEdit->$key ?>">
			</div>
		</div>
	</div>
	<?php 
	if ($colCount == 12):
	 $colCount = 0;?>
	</div>
<?php endif; ?>
	<?php
	endforeach; ?>


</form>
