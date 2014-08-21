<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title ?> | Admin</title>
<?php
    foreach ($config->styles as $file) {
        echo "    <link rel='stylesheet' href='{$file}' type='text/css'>\n";
    }
?>
</head>
<body>
    <div id="main">
        <form class='ui form segment form-login' method='POST'>
            <div class='field'>
                <label><i class='icon icon-user '></i></label>
                <input name='username' type='text' autocomplete='off' placeholder='Username'>
            </div>
            <div class='field'>
                <label><i class='icon icon-lock '></i></label>
                <input name='password' type='password' placeholder='Password'>
            </div>
            <button type='submit' class='ui button green fluid'>Login</button>
        </form>
    </div>
<?php foreach ($config->scripts as $file) {
    echo "    <script src='{$file}'></script>\n";
} ?>
</body>
</html>
