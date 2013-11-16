




<?php 

$fieldsList = $fields->all();

foreach ($fieldsList as $field) {
	$list .= "	<tr>
					<td><a href='{$adminUrl}fields/edit/?name={$field->name}'>{$field->name}</a></td>
					<td>{$field->label}</td>
					<td>{$field->fieldtype}</td>
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