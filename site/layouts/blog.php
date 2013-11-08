<?php include '_head.inc' ?>
<div class="container">
	<?php if ($page->children): ?>
		<?php foreach ($page->children as $p): ?>
			<div class="panel panel-default">
				<div class="panel-body">
					<h3><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></h3>
					<h6><?php echo $p->author ?> | <?php echo $p->published ?></h6>
					<hr>
					<?php echo $p->content ?>
				</div>
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>
<?php include '_foot.inc'; ?>