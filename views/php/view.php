<div class="span16">
	<ul class="breadcrumb span6">
<?php if( ! empty($belongs_to)): ?>
		<li>
			<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo Str::plural($belongs_to[0]); ?>/view/'.$<?php echo $singular; ?>-><?php echo $belongs_to[0]; ?>->id); ?>"><?php echo ucwords(str_replace('_', ' ', $belongs_to[0])); ?></a> <span class="divider">/</span>
		</li>
<?php endif; ?>
		<li>
			<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $plural; ?>'); ?>"><?php echo str_replace('_', ' ', $plural_class); ?></a> <span class="divider">/</span>
		</li>
		<li class="active">Viewing <?php echo str_replace('_', ' ', $singular_class); ?></li>
	</ul>
</div>

<div class="span16">
<?php foreach($fields as $field => $type): ?>
<p>
	<strong><?php echo ucfirst(str_replace('_', ' ', $field)); ?>:</strong>
<?php if($type != 'boolean'): ?>
	<?php echo '<?php'; ?> echo $<?php echo $singular; ?>-><?php echo $field; ?>; ?>
<?php else: ?>
	<?php echo '<?php'; ?> echo ($<?php echo $singular; ?>-><?php echo $field; ?>) ? 'True' : 'False'; ?>
<?php endif; ?>
</p>
<?php endforeach; ?>

<p><a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $plural; ?>/edit/'.$<?php echo $singular; ?>->id); ?>">Edit</a> | <a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $plural; ?>/delete/'.$<?php echo $singular; ?>->id); ?>" onclick="return confirm('Are you sure?')">Delete</a></p>
<?php foreach($plural_relationships as $relationship => $models): ?>
<?php foreach($models as $model): ?>
<h2><?php echo ucwords(str_replace('_', ' ', Str::plural($model))); ?></h2>

<?php echo '<?php'; ?> if(count($<?php echo $singular; ?>-><?php echo Str::plural($model); ?>) == 0): ?>
	<p>No <?php echo str_replace('_', ' ', Str::plural($model)); ?>.</p>
<?php echo '<?php else: ?>'.PHP_EOL; ?>
	<table>
		<thead>
<?php foreach(Scaffold\Table::fields(Str::plural($model)) as $field): ?>
<?php if($field != 'id' && $field != $singular.'_id'): ?>
			<th><?php echo ucwords(str_replace('_', ' ', $field)); ?></th>
<?php endif; ?>
<?php endforeach; ?>
			<th></th>
		</thead>

		<tbody>
			<?php echo '<?php'; ?> foreach($<?php echo $singular; ?>-><?php echo Str::plural($model); ?> as $<?php echo $model; ?>): ?>
				<tr>
<?php foreach(Scaffold\Table::fields(Str::plural($model)) as $field): ?>
<?php if($field != 'id' && $field != $singular.'_id'): ?>
					<td><?php echo '<?php'; ?> echo $<?php echo $model; ?>-><?php echo $field; ?>; ?></td>
<?php endif; ?>
<?php endforeach; ?>
					<td><a href="<?php echo '<?php'; ?> echo URL::to('<?php echo Str::plural($model); ?>/view/'.$<?php echo $model; ?>->id); ?>">View</a> <a href="<?php echo '<?php'; ?> echo URL::to('<?php echo Str::plural($model); ?>/edit/'.$<?php echo $model; ?>->id); ?>">Edit</a> <a href="<?php echo '<?php'; ?> echo URL::to('<?php echo Str::plural($model); ?>/delete/'.$<?php echo $model; ?>->id); ?>">Delete</a></td>
				</tr>
			<?php echo '<?php endforeach; ?>'.PHP_EOL; ?>
		</tbody>
	</table>
<?php echo '<?php endif; ?>'.PHP_EOL; ?>

<p><a class="btn success" href="<?php echo '<?php'; ?> echo URL::to('<?php echo Str::plural($model); ?>/create/'.$<?php echo $singular; ?>->id); ?>">Create new <?php echo str_replace('_', ' ', $model); ?></a></p>
<?php endforeach; ?>
<?php endforeach; ?>