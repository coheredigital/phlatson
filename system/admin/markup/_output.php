<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Admin</title>
		<?php foreach ($config->styles as $file) echo "<link rel='stylesheet' href='{$file}' type='text/css'>" ?>
		<?php foreach ($config->scripts as $file) echo "<script src='{$file}'></script>" ?>
	</head>
	<body>
		<div id="sidebar">
			<div class="sidebar-header">
				
			</div>
			<nav class="navbar" role="navigation">
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
				</ul>
			</nav>
		</div>

		<div id="main">
			<div class="main-header">
				<a class="header-button" href="<?php echo $config->urls->root ?>"><i class="icon icon-globe"></i></a>
			</div>
			<div class="main-content">
				<?php echo $output; ?>
			</div>
			<pre>
				
				<?php 
					var_dump($_GET['_url']);
					var_dump($input);
					$pageTest = $pages->get("contact");
					var_dump($pageTest);
				 ?>
			</pre>
		</div>


	</body>
</html>
