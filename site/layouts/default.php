<?php include '_head.inc' ?>

<div class="container">

	<?php 
		echo $page->content;

		if (count($page->files)) {
			foreach ($page->files as $file){
				var_dump($file);
			}
		}
		
	?>


	<?php if ($page->children): ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<h4>Child Pages</h4>
			<?php foreach ($page->children as $p): ?>
				<p><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></p>
			<?php endforeach ?>
		</div>
	</div>
	<?php endif ?>

	<?php if ($page->parent): ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<h4>Parent Page</h4>
			<p><a href="<?php echo $page->parent->url ?>"><?php echo $page->parent->title ?></a></p>
		</div>
	</div>
	<?php endif ?>

	<?php if ($page->rootParent): ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<h4>Root Parent Page</h4>
			<p><a href="<?php echo $page->rootParent->url ?>"><?php echo $page->rootParent->title ?></a></p>
		</div>
	</div>
	<?php endif ?>
</div>

<?php include '_foot.inc'; ?>