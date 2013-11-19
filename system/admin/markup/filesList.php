

<?php 

foreach ($files->all() as $file) {
	$output .= "<tr>";
	$output .= "<td>{$file->filename}</td>";
	$output .= "<td>{$file->type}</td>";
	$output .= "<td>$file->size</td>";
	$output .= "</tr>";
}



$output = "
<table>
	<tr>
		<th>Name</th>
		<th>Type</th>
		<th>Size</th>
	</tr>
	{$output}
</table>";