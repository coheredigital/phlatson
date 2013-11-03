<?php include '_head.inc'; ?>
<div class="container">
	<div class="jumbotron">
	  <div class="container">
	    <h1><?php echo $page->title ?></h1>
	    <h5><?php echo $page->author ?></h5>
	  </div>
	  <?php $page->children() ?>
	</div>
	<?php echo $page->content ?>
</div>
<?php include '_foot.inc'; ?>