<?php

$page->title = "Admin";

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>
			Admin
		</title>

		<link rel="stylesheet" href="/XPages/system/admin/styles/bootstrap.css" type="text/css">
		<link rel="stylesheet" href="/XPages/system/admin/scripts/redactor/redactor.css" type="text/css">
		<link rel="stylesheet" href="/XPages/system/admin/styles/adminTheme.css" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="<?php echo SITE_URL ?>/system/admin/scripts/bootstrap.js" type="text/javascript"></script>
		<script src="<?php echo SITE_URL ?>/system/admin/scripts/redactor/redactor.js" type="text/javascript"></script>
		<script src="<?php echo SITE_URL ?>/system/admin/scripts/XPages.js" type="text/javascript"></script>


	</head>
	<body>



		<nav class="navbar navbar-default navbar-static" role="navigation">
			<div class="container">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
						</button>
						<a class="navbar-brand" href="<?php echo SITE_URL ?>">XPages</a>
					</div>

					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="active">
								<a href="<?php echo ADMIN_URL ?>">Pages</a>
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

					</div>
				</nav>
			</div>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php

					if ($_GET["edit"]) {
						include 'markup/edit.php';
					}
					else{
						include 'markup/pagetree.php';
					}



					?>
				</div>
			</div>

		</div>

	</body>
</html>