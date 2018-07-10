<div class="container">
	<?php if ($page->content): ?>
		<?= $page->content ?>
		<hr>
	<?php endif; ?>
	<?php if ($page->markdown) : ?>
		<?= $page->markdown ?>
		<hr>
	<?php endif; ?>
	<?php if ($page->children()->count()): ?>
	<h4>Children</h4>
	<ul>
		<?php foreach ($page->children() as $key => $p) : ?>
			<li><a href="<?= $p->url ?>"><?= $p->title ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

</div>