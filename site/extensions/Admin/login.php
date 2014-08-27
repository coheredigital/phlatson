<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title ?> | Admin</title>
<?php

foreach ($config->styles as $file) {
    echo "    <link rel='stylesheet' href='{$file}' type='text/css'>\n";
}

$usernameInput = $extensions->get("FieldtypeText");
$usernameInput->name = "username";
$usernameInput->label = false;
$usernameInput->attribute("autocomplete","off");
$usernameInput->attribute("placeholder","Username");

$passwordInput = $extensions->get("FieldtypePassword");
$passwordInput->name = "password";
$passwordInput->label = false;
$passwordInput->attribute("autocomplete","off");
$passwordInput->attribute("placeholder","Password");

?>
</head>
<body>
    <div id="main">
        <form class='ui form segment form-login' method='POST'>
            <div class="logo">
                <img src="<?php echo $this->url ?>styles/images/logo.png" alt=""/>
            </div>
            <?php
            echo $usernameInput->render();
            echo $passwordInput->render();
            ?>
            <button type='submit' class='ui button green fluid'>Login</button>
        </form>
    </div>
<?php foreach ($config->scripts as $file) {
    echo "    <script src='{$file}'></script>\n";
} ?>
</body>
</html>
