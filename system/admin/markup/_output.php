<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $admin->title ?> | Admin</title>
		<?php foreach ($config->styles as $file) echo "<link rel='stylesheet' href='{$file}' type='text/css'>" ?>
		<?php foreach ($config->scripts as $file) echo "<script src='{$file}'></script>" ?>
	</head>
	<body>
		<div id="header">
			<nav class="navbar" role="navigation">
				<ul class="nav navbar-nav">
					<li>
						<a href="<?php echo $adminHome->url ?>"><?php echo $adminHome->title ?></a>
					</li>
					<?php foreach ($adminHome->children as $p): ?>
						<?php $class = $p->url == $admin->rootParent->url ? "class='active'" : "" ?>
						<li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
					<?php endforeach ?>
				</ul>
			</nav>
		</div>

		<div id="main">
		<!-- 	<div class="main-header">
				<a class="header-button" href="<?php echo $config->urls->root ?>"><i class="icon icon-globe"></i></a>
			</div> -->
			<div class="main-content">
				<?php echo $output; ?>
			</div>
		</div>


	</body>
</html>
