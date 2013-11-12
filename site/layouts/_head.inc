
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


		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		    <ul class="nav navbar-nav">
				<?php foreach ($home->children as $p): ?>
					<?php $class = $p->url(false) == $page->rootParent->url(false) ? "class='active'" : "" ?>
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
	<div id="main">
		<div class="container">
			<?php $parents = $page->parents() ?>
			<?php if (count($parents) && $page->url != $home->url): ?>
			<ol class="breadcrumb">
				
				
				<?php foreach ($parents as $p): ?>
					<li><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
				<?php endforeach ?>
				<li><?php echo $page->title ?></li>

			</ol>
			<?php endif ?>
		</div>
