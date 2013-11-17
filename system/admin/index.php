<?php require_once 'includes/init.php' ?>
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
						<a href="<?php echo $config->urls->root.$config->adminUrl ?>">Content</a>
					</li>
					<li>
						<a href="<?php echo $config->urls->root.$config->adminUrl ?>/fields/">Fields</a>
					</li>
					<li>
						<a href="<?php echo $config->urls->root.$config->adminUrl ?>/templates/">Templates</a>
					</li>
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

