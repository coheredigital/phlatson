<?php 
$template = $page->template;
?>
<h4><?= str_replace("Phlatson\\","", get_class($object))  ?></h4>
<pre><table>
	<?php foreach ($properties as $property): ?>
	<tr>
		<th><?= $property ?></th>
		<td><?= $object->{$property} ?></td>
	</tr>
	<?php endforeach; ?>
</table></pre>