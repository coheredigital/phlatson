<?php

	$url = str_replace(SITE_URL, "", $_GET["edit"])."/";
	$pageEdit = $pages->get($url);

 ?>
<form action="" role="form">
	<script type="text/javascript">
	$(function(){
		$('#Field_Body').redactor();
	});

	</script>

	<div class="panel panel-default">
		<div class="panel-heading">Title</div>
		<div class="panel-body">
			<input class="form-control" type="text" name="title" id="" value="<?php echo $pageEdit->title ?>">
		</div>
	</div>


	<div class="panel panel-default">
		<div class="panel-heading">Content</div>
		<div class="panel-body">
			<textarea name="Richtext" id="Field_Body" cols="30" rows="10">
				<?php echo $pageEdit->content ?>
			</textarea>
		</div>

	</div>




</form>
