<div class="container">
	<?php if ($page->content): ?>
		<?= $page->content ?>
		<hr>
	<?php endif; ?>
	
	<pre><table>
		<tr>
			<th>$page->name</th>
			<td><?= $page->name ?></td>
		</tr>
		<tr>
			<th>$page->url</th>
			<td><?= $page->url ?></td>
		</tr>
		<tr>
			<th>$page->folder</th>
			<td><?= $page->folder ?></td>
		</tr>
		<tr>
			<th>$page->path</th>
			<td><?= $page->path ?></td>
		</tr>
		<tr>
			<th>$page->file</th>
			<td><?= $page->file ?></td>
		</tr>
		<tr>
			<th>$page->filename</th>
			<td><?= $page->filename ?></td>
		</tr>

	</table>
	</pre>
	<?php if ($page->children()->count()): ?>
	<h4>Children</h4>
	<ul>
		<?php foreach ($page->children() as $key => $p) : ?>
			<li><a href="<?= $p->url ?>"><?= $p->title ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

</div>