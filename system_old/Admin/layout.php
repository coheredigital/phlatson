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


<?php if (count($this->messages)): ?>
    <div class="messages">
        <div class="container">
            <?php foreach ($this->messages as $message): ?>
                <div><?php echo $message ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<div id="header">
    <div class="main-menu menu">
    
        <?php


        $menuLinks = [
            "Pages" => [
                "icon" => "edit",
                "url" => $router->pages->url
            ],
            "Fields" => [
                "icon" => "edit",
                "url" => $router->fields->url
            ],
            "Templates" => [
                "icon" => "code",
                "url" => $router->templates->url
            ],
            "Extensions" => [
                "icon" => "cubes",
                "url" => $router->extensions->url
            ],
            "Users" => [
                "icon" => "users",
                "url" => $router->users->url
            ],
            "Settings" => [
                "icon" => "cog",
                "url" => $router->settings->url
            ]
        ];

        ?>
        <div class="container">
        <?php foreach ($menuLinks as $key => $value): ?>
            <?php $class = strpos($request->url , $value['url']) !== FALSE ? "active" : "" ?>
            <a class="item <?php echo $class ?>" href="<?php echo $value['url'] ?>">
                <i class="icon icon-<?php echo $value['icon'] ?>"></i>
                <?php echo $key ?>
            </a>
        <?php endforeach; ?>
        </div>


    </div>

</div>
<div id="main">
    <?php echo $this->output ?>
</div>
    <div class="user-menu">
        <div class="container">
            <div class="user-menu-content">
                <div class="user-name"><?php echo $user->name ?></div>
                <a href="<?php echo $config->urls->admin ?>logout" class="user-logout"><i class="icon icon-lock"></i> Logout</a>
            </div>
        </div>

    </div>
<?php
foreach ($config->scripts as $file) {
    echo "\n    <script src='{$file}'></script>";
} ?>
</body>
</html>
