<div class="ui menu">


    <?php foreach ($adminHome->children as $p): ?>
        <?php
        $rootParent = $page->rootParent;
        $state = $p->url == $rootParent->url ? "active" : "";
        ?>
        <a class="item <?php echo $state ?>"  href="<?php echo $p->url ?>"><?php echo $p->title ?></a>
    <?php endforeach ?>

    <div class="right menu">
        <div class="ui dropdown item">
            <i class="icon user"></i> <?php echo $user->name ?> <i class="icon dropdown"></i>
            <div class="menu">
                <a class="item"><i class="edit icon"></i> Edit Profile</a>
                <a href="?logout=1" class="item"><i class="settings icon"></i> Logout</a>
            </div>
        </div>
    </div>


</div>