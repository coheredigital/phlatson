<?php

$page->title = "Admin";
$page->adminTemplate = 'markup/pagetree.php';

if ($page->requests[1] == "edit") {

	$page->adminTemplate = 'markup/edit.php';
}




?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Admin</title>
		<link rel="stylesheet" href="/XPages/system/admin/scripts/redactor/redactor.css" type="text/css">
		<link rel="stylesheet" href="/XPages/system/admin/styles/adminTheme.css" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="<?php echo SITE_URL ?>/system/admin/scripts/redactor/redactor.js" type="text/javascript"></script>
		<script src="<?php echo SITE_URL ?>/system/admin/scripts/XPages.js" type="text/javascript"></script>


	</head>
	<body>



		<nav class="navbar" role="navigation">
			<div class="container">
				<ul class="nav navbar-nav">
					<li class="active">
						<a href="<?php echo ADMIN_URL ?>">Pages</a>
					</li>
					<li>
						<a href="#">Setting</a>
					</li>
				</ul>
			</div>
		</nav>
			
		<div id="main">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php include($page->adminTemplate) ?>
					</div>
				</div>

			</div>
		</div>


	</body>
</html>