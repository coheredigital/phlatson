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
				<a href="<?php echo $config->urls->root ?>" class="pull-right button">View Site</a>
				<ul class="nav navbar-nav">
					<li>
						<a href="<?php echo $config->urls->root.$config->adminUrl ?>">Content</a>
					</li>
					<li>
						<a href="<?php echo $config->urls->root.$config->adminUrl ?>/fields/">Fields</a>
					</li>
					<li><a href="#">Setting</a></li>
				</ul>
			</div>
		</nav>
			
		<div id="main">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php echo $output; ?>
					</div>
				</div>

			</div>
		</div>


	</body>
</html>

