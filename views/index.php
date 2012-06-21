<table>
	<thead>
		<tr>
			<th>Table</th>
			<th>Items</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($tables as $table): ?>
			<tr>
				<td><a href="<?php echo URL::to('scaffold/table/'.$table->name); ?>"><?php echo $table->name; ?></a></td>
				<td><?php echo $table->items; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>