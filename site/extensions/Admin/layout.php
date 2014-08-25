<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title ?> | Flatbed</title>
    <?php
    foreach ($config->styles as $file) {
        echo "    <link rel='stylesheet' href='{$file}' type='text/css'>\n";
    }
    ?>
</head>
<body>
    <div id="header">
        <div class="logo">
            <img src="<?php echo $this->url ?>styles/images/logo.png" alt=""/>
        </div>
    </div>
    <div id="sidebar">
        <div class="ui menu vertical main-menu">
            <a class="item" href="<?php echo $config->urls->admin ?>pages">
                <i class="icon icon-file"></i>
                Pages
            </a>
            <a class="item" href="<?php echo $config->urls->admin ?>fields">
                <i class="icon icon-edit"></i>
                Fields
            </a>
            <a class="item" href="<?php echo $config->urls->admin ?>templates">
                <i class="icon icon-code"></i>
                Templates
            </a>
            <a class="item" href="<?php echo $config->urls->admin ?>extensions">
                <i class="icon icon-cubes"></i>
                Extensions
            </a>
            <a class="item" href="<?php echo $config->urls->admin ?>settings">
                <i class="icon icon-cog"></i>
                Settings
            </a>
        </div>
    </div>
    <div id="main">
        <?php echo $this->output ?>
    </div><?php
foreach ($config->scripts as $file) {
    echo "\n    <script src='{$file}'></script>";
}?>
</body>
</html>
