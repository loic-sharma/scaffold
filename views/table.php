<table>
	<thead>
		<tr>
			<?php foreach($fields as $field): ?>
				<th><?php echo $field->name; ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>

	<tbody>
		<?php foreach($rows as $row): ?>
			<?php if($editable): ?>
				<tr onclick="window.location = '<?php echo URL::to('crud/table/'.$table.'/edit/'.$row->id); ?>'">
			<?php else: ?>
				<tr>
			<?php endif; ?>

				<?php foreach($fields as $field): ?>
					<td><?php echo $row->{$field->name}; ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>