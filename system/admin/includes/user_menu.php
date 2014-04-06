<?php if ($user->isLoggedin()): ?>

    <li class="user-menu">
        <i class="icon icon-user"></i> <a href="<?php echo $user->name ?>"><?php echo $user->name ?></a> <span
            class="seperator">|</span> <a class="action" href="?logout=1">Logout</a>
    </li>

<?php endif ?>