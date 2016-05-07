<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title ?> | Admin</title>
<?php

foreach ($this->api("config")->styles as $file) {
    echo "    <link rel='stylesheet' href='{$file}' type='text/css'>\n";
}

$usernameInput = $this->api("extensions")->get("InputText");
$usernameInput->label = false;
$usernameInput->attribute("name","username");
$usernameInput->attribute("autocomplete","off");
$usernameInput->attribute("placeholder","Username");

$passwordInput = $this->api("extensions")->get("InputPassword");
$passwordInput->label = false;
$passwordInput->attribute("name","password");
$passwordInput->attribute("autocomplete","off");
$passwordInput->attribute("placeholder","Password");

?>
</head>
<body>
    <div id="main">
        <form class='ui form segment form-login' method='POST'>
            <?php
            echo $usernameInput->render();
            echo $passwordInput->render();
            ?>
            <button type='submit' class='ui button green fluid'>Login</button>
        </form>
    </div>
<?php foreach ($this->api("config")->scripts as $file) {
    echo "    <script src='{$file}'></script>\n";
} ?>
</body>
</html>
