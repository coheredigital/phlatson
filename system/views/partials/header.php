<div id="header">
    <div class="main-menu menu">
    
        <?php


        $menuLinks = [
            "Pages" => [
                "icon" => "edit",
                "url" => "{$page->url}pages"
            ],
            "Fields" => [
                "icon" => "edit",
                "url" => "{$page->url}fields"
            ],
            "Templates" => [
                "icon" => "code",
                "url" => "{$page->url}templates"
            ],
            "Extensions" => [
                "icon" => "cubes",
                "url" => "{$page->url}extensions"
            ],
            "Users" => [
                "icon" => "users",
                "url" => "{$page->url}users"
            ],
            "Settings" => [
                "icon" => "cog",
                "url" => "{$page->url}settings"
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