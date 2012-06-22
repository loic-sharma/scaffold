<div class="span16">
	<ul class="breadcrumb span6">
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