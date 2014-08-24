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
        <div class="container">
            <div class="ui menu main-menu">
                <a class="item" href="<?php echo $config->urls->admin ?>pages">Pages</a>
                <a class="item" href="<?php echo $config->urls->admin ?>fields">Fields</a>
                <a class="item" href="<?php echo $config->urls->admin ?>templates">Templates</a>
                <a class="item" href="<?php echo $config->urls->admin ?>extensions">Extensions</a>
                <a class="item" href="<?php echo $config->urls->admin ?>settings">Settings</a>
            </div>
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
