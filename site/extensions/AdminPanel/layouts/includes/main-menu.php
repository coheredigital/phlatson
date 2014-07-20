<div class="container">
    <div class="ui menu">
        <a class="item"  href="<?php echo $config->urls->root . $this->adminUrl ?>">Content</a>
        <a class="item"  href="<?php echo $config->urls->root . $this->adminUrl ?>/settings">Settings</a>
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
</div>