<?php 
$view = $page->template->view;
?>
<h4>View</h4>
<pre><table>
	<tr>
		<th>$view->name</th>
		<td><?= $view->name ?></td>
	</tr>
	<tr>
		<th>$view->url</th>
		<td><?= $view->url ?></td>
	</tr>
	<tr>
		<th>$view->folder</th>
		<td><?= $view->folder ?></td>
	</tr>
	<tr>
		<th>$view->path</th>
		<td><?= $view->path ?></td>
	</tr>
	<tr>
		<th>$view->rootPath</th>
		<td><?= $view->rootPath ?></td>
	</tr>
	<tr>
		<th>$view->file</th>
		<td><?= $view->file ?></td>
	</tr>
	<tr>
		<th>$view->filename</th>
		<td><?= $view->filename ?></td>
	</tr>
</table></pre>