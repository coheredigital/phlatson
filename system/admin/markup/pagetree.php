<?php
$home = $pages->get("/");

function PageTreeItemTitle($title, $url){
	return 	"<div class='page-tree-item'>
				<a href='http://localhost/XPages/admin/edit/?page=$url'>
				$title
				</a>
			</div>";
}


?>
<div class="page-tree">
	<ul class="page-tree-list">
		<li class="page-tree-group">
			<?php echo PageTreeItemTitle($home->title, $home->directory) ?>
			<?php if ($home->children): ?>
			<ul class="page-tree-list">

			<?php foreach ($home->children as $p): ?>
				<li class="page-tree-group">

					<?php echo PageTreeItemTitle($p->title, $p->directory) ?>

					<?php if ($p->children): ?>
					<ul class="page-tree-list">
					<?php foreach ($p->children as $p): ?>
						<li class="page-tree-group">
							<?php echo PageTreeItemTitle($p->title, $p->directory) ?>
						</li>
					<?php endforeach ?>
					</ul>
					<?php endif ?>

				</li>
			<?php endforeach ?>
			</ul>
			<?php endif ?>
		</li>
	</ul>
</div>

