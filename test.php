<?php 


$data = new Page("file:///C:/Users/Adam/Documents/GitHub/jPage/data/test.xml");
$page = $data->data;
 ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $page->title ?></title>
</head>
<body>
	<h1><?php echo $page->title ?></h1>
	
</body>
</html>