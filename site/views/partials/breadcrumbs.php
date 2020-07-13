<div class="breadcrumbs">
<?php foreach ($page->parents() as $p) : ?>
	<a href="<?= $p->url() ?>"><?= $p->title ?></a> /
<?php endforeach; ?>
</div>