<?php include '_head.inc'; ?>

<div class="container">

	<h6><?php echo $page->date ?></h6>
	<hr>
	<hr>
	<?php echo $page->content ?>
	
	<?php if ($page->children): ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<h4>Child Pages</h4>
			<ul>
				<?php foreach ($page->children as $p): ?>
					<li><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>	
	<?php endif ?>

	<?php $page->parent() ?>


</div>

<?php include '_foot.inc'; ?>