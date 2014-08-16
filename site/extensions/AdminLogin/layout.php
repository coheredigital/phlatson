<?php

if ($input->get->logout == 1) {
    $session->logout();
    $session->redirect($config->urls->root . $this->adminUrl);
}
if ($user->isGuest()) {
//    $session->redirect($config->urls->root . $this->adminUrl . "/login");
}

$admin = api("admin");

// admin scripts and styles
$config->styles->add("{$admin->url}styles/adminTheme.css");
$config->styles->add("{$admin->url}styles/semantic.min.css");
$config->styles->append("{$admin->url}styles/font-awesome-4.1.0/css/font-awesome.css");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title ?> | Admin</title>
    <?php foreach ($config->styles as $file) {
        echo "<link rel='stylesheet' href='{$file}' type='text/css'>";
    } ?>

</head>
<body>
<div id="main">
    <?php echo $this->output ?>
</div>
<?php foreach ($config->scripts as $file) {
    echo "<script src='{$file}'></script>";
} ?>
</body>
</html>
