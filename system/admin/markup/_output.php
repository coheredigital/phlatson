<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Admin</title>
		<?php foreach ($config->styles as $file) echo "<link rel='stylesheet' href='{$file}' type='text/css'>" ?>
		<?php foreach ($config->scripts as $file) echo "<script src='{$file}'></script>" ?>
	</head>
	<body>
		<nav class="navbar" role="navigation">
			<div class="container">
				<ul class="nav navbar-nav">
					<li>
						<a href="<?php echo $adminHome->url ?>"><?php echo $adminHome->title ?></a>
					</li>
					<?php foreach ($adminHome->children as $p): ?>
						<?php 
						// var_dump("{$p->url} == {$admin->rootParent->url}");
						 ?>
						<?php $class = $p->url == $admin->rootParent->url ? "class='active'" : "" ?>
						<li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
					<?php endforeach ?>
					<li class="pull-right">
						<a href="<?php echo $config->urls->root ?>">View Site</a>
					</li>
				</ul>
			</div>
		</nav>
		<div id="main">
			<div class="container">
				<?php echo $output; ?>
			</div>
		</div>
	</body>
</html>
