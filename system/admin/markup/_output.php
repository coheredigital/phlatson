<?php 

	// var_dump($admin->layout);
 ?>

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
			<div class="navbar" role="navigation">
				<div class="container">
					<ul class="nav navbar-nav">
						<?php foreach ($adminHome->children as $p): ?>
							<?php $class = $p->url == $admin->rootParent->url ? "class='active'" : "" ?>
							<li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>

		<div id="main">
			<div class="container">
				<?php echo $output; ?>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<p>XPages © Adam Spruijt - 2013</p>
			</div>
		</div>
	</body>
</html>
