<?php include '_head.inc'; ?>

<div class="container">
	<?php
	$home = $pages->get("/");
	?>
	 <h2><a href="<?php echo $home->url ?>"><?php echo $home->title ?></a></h2>
	 <h6><?php echo $page->date ?></h6>
	<hr>
	<hr>
	<?php echo $page->content ?>
	<p>

		<ul>
			<?php foreach ($page->children as $p): ?>
				<li><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
			<?php endforeach ?>
		</ul>
	</p>


</div>

<?php include '_foot.inc'; ?>