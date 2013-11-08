
	</div>
	<div class="footer">
		<p>XPages Demo Site &copy; <?php echo date('Y'); ?></p>
	</div>
		<?php

		if($config->debug){
			$debugbar['messages']->info($input);
			$debugbar['messages']->info($page);
			$debugbar['messages']->info($config);
			echo $debugbarRenderer->render();
		} ?>
</body>
</html>
