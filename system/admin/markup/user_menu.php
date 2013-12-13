<?php if ($user->isLoggedin()): ?>
<div>
	<p><?php echo $user->name ?></p>
	<p><a href="?logout=1">Logout</a></p>	
</div>
<?php endif ?>