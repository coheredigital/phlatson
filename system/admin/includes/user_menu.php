<?php if ($user->isLoggedin()): ?>

    <li class="user-menu">
        <a href="<?php echo $user->name ?>"><i class="icon icon-user"></i>  <?php echo $user->name ?></a><br>
        <a class="action" href="?logout=1">Logout</a>
    </li>

<?php endif ?>