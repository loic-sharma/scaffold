<?php echo '<?php'; ?> if(count($<?php echo $this->plural; ?>) == 0): ?>
	<p>No <?php echo $this->plural_class; ?></p>
<?php echo '<?php else: ?>'; ?>
	<table>
		<thead>
			<tr>
	<?php foreach($this->fields as $field => $type): ?>
				<th><?php echo ucwords($field); ?></th>
	<?php endforeach; ?>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?php echo '<?php'; ?> foreach($<?php echo $this->plural; ?> as $<?php echo $this->singular; ?>): ?>
				<tr>
	<?php foreach($this->fields as $field => $type): ?>
					<td><?php echo '<?php'; ?> echo $<?php echo $this->singular; ?>-><?php echo $field; ?>; ?></td>
	<?php endforeach; ?>
					<td>
						<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>/view/'.$<?php echo $this->singular; ?>->id); ?>">View</a>
						<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>/edit/'.$<?php echo $this->singular; ?>->id); ?>">Edit</a>
						<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>/delete/'.$<?php echo $this->singular; ?>->id); ?>" onclick="return confirm('Are you sure?')">Delete</a>
					</td>
				</tr>
			<?php echo '<?php endforeach; ?>'; ?>
		</tbody>
	</table>
<?php echo '<?php endif; ?>'; ?>

<p><a class="btn success" href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>/create'); ?>">Create new <?php echo $this->singular_class; ?></a></p>