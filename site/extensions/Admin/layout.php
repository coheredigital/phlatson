<?php

if ($input->get->logout == 1) {
    $session->logout();
    $session->redirect($config->urls->root . $this->adminUrl);
}
if ($user->isGuest()) {
//    $session->redirect($config->urls->root . $this->adminUrl . "/login");
}



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
<div id="header">
    <div class="container">
        <div class="ui menu">
            <a class="item" href="<?php echo $config->urls->root . $config->adminUrl ?>/pages">Pages</a>
            <a class="item" href="<?php echo $config->urls->root . $config->adminUrl ?>/fields">Fields</a>
            <a class="item" href="<?php echo $config->urls->root . $config->adminUrl ?>/templates">Templates</a>
            <a class="item" href="<?php echo $config->urls->root . $config->adminUrl ?>/extensions">Extensions</a>
            <a class="item" href="<?php echo $config->urls->root . $config->adminUrl ?>/settings">Settings</a>

            <div class="right menu">
                <div class="ui dropdown item">
                    <i class="icon user"></i> <?php echo $user->name ?> <i class="icon dropdown"></i>

                    <div class="menu">
                        <a class="item"><i class="edit icon"></i> Edit Profile</a>
                        <a href="<?php echo $config->urls->root . $config->adminUrl ?>/logout" class="item"><i
                                class="settings icon"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h1><?php echo $this->title ?></h1>
    </div>
</div>
<div id="main">
    <?php echo $this->output ?>
</div>
<?php foreach ($config->scripts as $file) {
    echo "<script src='{$file}'></script>";
} ?>
</body>
</html>
