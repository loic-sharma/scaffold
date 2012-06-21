<div class="span16">
	<ul class="breadcrumb span6">
		<li>
			<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>'); ?>"><?php echo $this->plural_class; ?></a> <span class="divider">/</span>
		</li>
		<li class="active">Viewing <?php echo $this->singular_class; ?></li>
	</ul>
</div>

<div class="span16">
<?php foreach($this->fields as $field => $type): ?>
<p>
	<strong><?php echo $field; ?>:</strong>
	<?php echo '<?php'; ?> echo $<?php echo $this->singular; ?>-><?php echo $field; ?>; ?>
</p>
<?php endforeach; ?>

<p><a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>/edit/'.$<?php echo $this->singular; ?>->id); ?>">Edit</a> | <a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>/delete/'.$<?php echo $this->singular; ?>->id); ?>" onclick="return confirm('Are you sure?')">Delete</a></p>