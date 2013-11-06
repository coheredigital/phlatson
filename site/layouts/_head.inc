<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>XTest - <?php //echo $page->title ?></title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="/XPages/site/layouts/styles/main.css">
</head>
<body>

	<?php $home = $pages->get("/") ?>
	<nav id="nav" class="navbar navbar-default  navbar-static-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $home->url ?>">XPages</a>
			</div>
		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		    <ul class="nav navbar-nav">
				<?php foreach ($home->children as $p): ?>
					<!-- TODO: Fix typecasting requirement here -->
					<?php $class = $p->url == $page->url ? "class='active'" : "" ?>
					<li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
				<?php endforeach ?>
		    </ul>
		  </div>
		</div>




	</nav>

	<div class="header">

	  <div class="container">
	    <h1><?php echo $page->title ?></h1>
	  </div>
	</div>
