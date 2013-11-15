<?php require_once 'includes/init.php' ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Admin</title>

		<?php foreach ($config->styles as $file): ?>
			<link rel="stylesheet" href="<?php echo $file ?>" type="text/css">
		<?php endforeach ?>

		<?php foreach ($config->scripts as $file): ?>
			<script src="<?php echo $file ?>"></script>
		<?php endforeach ?>
	</head>
	<body>
		<nav class="navbar" role="navigation">
			<div class="container">
				<a href="<?php echo $config->urls->root ?>" class="pull-right button">View Site</a>
				<ul class="nav navbar-nav">
					<li class="active">
						<a href="<?php echo ADMIN_URL ?>">Pages</a>
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

