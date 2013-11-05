<?php include '_head.inc'; ?>

<div class="container">
	<h1><?php echo $page->title ?></h1>
	<?php
		$home = $pages->get("/");

	 ?>
	 <h2><a href="<?php echo $home->url ?>"><?php echo $home->title ?></a></h2>
	 <h6><?php echo $page->published ?></h6>
	<hr>
	<hr>
	<?php echo $page->content ?>

		<p><?php echo $page->children ?></p>


</div>

<?php include '_foot.inc'; ?>