<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Flatbed CMS</title>
	<?php foreach ($config->styles as $file): ?>
		<link rel="stylesheet" href="<?= $file ?>">
	<?php endforeach ?>
</head>
<body>
	<?= $this->main ?>
	<?= $views->render('partials/user-menu') ?>
</body>
</html>