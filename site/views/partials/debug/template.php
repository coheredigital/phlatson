<?php 
$template = $page->template;
?>
<h4>Template</h4>
<pre><table>
	<?php foreach ($properties as $property): ?>
	<tr>
		<th>$template-><?= $property ?></th>
		<td><?= $template->{$property} ?></td>
	</tr>
	<?php endforeach; ?>
</table></pre>