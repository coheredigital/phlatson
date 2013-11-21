<?php 

$templatesList = $templates->all();

foreach ($templatesList as $template) {
	$list .= "	<tr>
					<td><a href='{$adminUrl}templates/edit/?name={$template->name}'>{$template->name}</a></td>
					<td>{$template->label}</td>
					<td>{$template->templatetype}</td>
				</tr>";
}


$output = "<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Label</th>
					<th>Feildtype</th>
				</tr>
			</thead>
			<tbody>
				{$list}
			</tbody>
		</table>";