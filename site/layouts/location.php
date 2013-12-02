<?php include 'includes/head.inc' ?>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
		<?php 
		$user = $users->get("adam");
		var_dump($user);
		var_dump($user->pass);
		?>
		</div>
	</div>
</div>
<?php include 'includes/foot.inc'; ?>