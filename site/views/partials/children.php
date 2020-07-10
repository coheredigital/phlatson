<div class="container">
	<?php if ($page->content): ?>
		<?= $page->content ?>
		<hr>
	<?php endif; ?>
	<?php if ($page->markdown) : ?>
		<?= $page->markdown ?>
		<hr>
	<?php endif; ?>
</div>