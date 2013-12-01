<?php include 'includes/head.inc' ?>
<div class="container">
	<?php if ($page->children): ?>
		<?php foreach ($page->children as $p): ?>
			<div class="panel panel-default">
				<div class="panel-body">
					<h3><a href="<?= $p->url ?>"><?= $p->title ?></a></h3>
					<h6><?= $p->author ?> | <?= $p->published ?></h6>
					<hr>
					<?= $p->content ?>
				</div>
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>
<?php include 'includes/foot.inc'; ?>