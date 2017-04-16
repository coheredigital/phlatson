<?php 

if ($request->segment(1) == "fields") {


	$view = $views->get('admin.fields');
	echo $view->render($page);
}
