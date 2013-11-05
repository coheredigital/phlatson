<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>
			Admin
		</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="/XPages/system/admin/scripts/bootstrap.js" type="text/javascript"></script>
		<script src="/XPages/system/admin/scripts/redactor/redactor.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/XPages/system/admin/styles/bootstrap.css" type="text/css">
		<link rel="stylesheet" href="/XPages/system/admin/scripts/redactor/redactor.css" type="text/css">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<nav class="navbar navbar-default" role="navigation">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span></button> <a class="navbar-brand" href="#">XPages</a>
					</div><!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="active">
								<a href="#">Pages</a>
							</li>
							<li>
								<a href="#">Setting</a>
							</li>
						</ul>
						<form class="navbar-form navbar-right" role="search">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Search">
							</div>
						</form>
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="#">Link</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Action</a>
									</li>
									<li>
										<a href="#">Another action</a>
									</li>
									<li>
										<a href="#">Something else here</a>
									</li>
									<li>
										<a href="#">Separated link</a>
									</li>
								</ul>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</nav>
			</div>

			<h2>
				Test
			</h2>

			<script type="text/javascript">
			$(function(){
				$('#Field_Body').redactor();
			});

			</script>
			<textarea name="Richtext" id="Field_Body" cols="30" rows="10"></textarea>
		</div>
		<?php if ($config->debug): ?>
			<hr>
			<pre>
				<?php
					var_dump($config);
					var_dump($page);
				 ?>

			</pre>
		<?php endif ?>
	</body>
</html>