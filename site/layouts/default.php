<?php include '_head.inc' ?>
<div class="container">
	<!-- PAGE CONTENT -->
	<?php echo $page->content ?>
	<!-- CHILDREN -->
	<?php if ($page->children): ?>
	<hr>
	<div class="panel panel-default">
		<div class="panel-body">
			<h4>Child Pages</h4>
			<?php foreach ($page->children as $p): ?>

				<p><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></p>
			<?php endforeach ?>
		</div>
	</div>
	<?php endif ?>
	<!-- end CHILDREN -->
</div>
<?php include '_foot.inc'; ?>