<?php
$home = $pages->get("/");

function PageTreeItemTitle($title, $url){
	return 	"<div class='panel panel-default'>
				<div class='panel-body'>
					<a href='?edit=$url'>
					$title
					</a>
				</div>
			</div>";
}


?>
	<div class="PageTree">
		<ul class="PageTreeList">
			<li class="PageTreeItem">
				<?php echo PageTreeItemTitle($home->title, $home->url) ?>
				<?php if ($home->children): ?>
				<ul class="PageTreeList">

				<?php foreach ($home->children as $p): ?>
					<li class="PageTreeItem">

						<?php echo PageTreeItemTitle($p->title, $p->url) ?>

						<?php if ($p->children): ?>
						<ul class="PageTreeList">
						<?php foreach ($p->children as $p): ?>
							<li class="PageTreeItem">
								<?php echo PageTreeItemTitle($p->title, $p->url) ?>
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

