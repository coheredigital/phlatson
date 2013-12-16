<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $admin->title ?> | Admin</title>
		<?php foreach ($config->styles as $file) echo "<link rel='stylesheet' href='{$file}' type='text/css'>" ?>
		<?php foreach ($config->scripts as $file) echo "<script src='{$file}'></script>" ?>
	</head>
	<body class="<?php echo "page-{$page->name}" ?>">
		<?php if (!$user->isGuest()): ?>
		<div id="header" class="clearfix">
			
			<div class="navbar" role="navigation">
				<ul class="nav navbar-nav uk-navbar-nav">
					<?php include "user_menu.php" ?>	
					<?php foreach ($adminHome->children as $p): ?>
						<?php $class = $p->url == $admin->rootParent->url ? "class='active'" : "" ?>
						<li <?php echo $class ?>><a href="<?php echo $p->url ?>"><?php echo $p->title ?></a></li>
					<?php endforeach ?>		

				</ul>
				
			</div>
		</div>
		<div id="main">
			<div class="container">
		<?php endif ?>


		
				<?php echo $output; ?>

		<?php if (!$user->isGuest()): ?>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<p>XPages © Adam Spruijt - 2013</p>
				
			</div>			
		</div>
		<?php endif ?>
	</body>
</html>
