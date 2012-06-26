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
		<li class="active">Editing <?php echo str_replace('_', ' ', $singular_class); ?></li>
	</ul>
</div>

<?php echo '<?php'; ?> echo Form::open(null, 'post', array('class' => 'form-stacked')); ?>
	<fieldset>
<?php foreach($fields as $field => $type): ?>
		<div class="clearfix">
			<?php echo '<?php'; ?> echo Form::label('<?php echo $field; ?>', '<?php echo ucwords(str_replace('_', ' ', $field)); ?>'); ?>

			<div class="input">
<?php if(in_array($type, array('string', 'integer', 'float', 'date', 'timestamp'))): ?>
				<?php echo '<?php'; ?> echo Form::text('<?php echo $field; ?>', Input::old('<?php echo $field; ?>', $<?php echo $singular; ?>-><?php echo $field; ?>), array('class' => 'span6')); ?>
<?php elseif($type == 'boolean'): ?>
				<?php echo '<?php'; ?> echo Form::checkbox('<?php echo $field; ?>', '1', Input::old('<?php echo $field; ?>', $<?php echo $singular; ?>-><?php echo $field; ?>)); ?>
<?php elseif($type == 'text' || $type == 'blob'): ?>
				<?php echo '<?php'; ?> echo Form::textarea('<?php echo $field; ?>', Input::old('<?php echo $field; ?>', $<?php echo $singular; ?>-><?php echo $field; ?>), array('class' => 'span10')); ?>
<?php endif; ?>
			</div>
		</div>
<?php endforeach; ?>

		<div class="actions">
			<?php echo '<?php'; ?> echo Form::submit('Save', array('class' => 'btn primary')); ?>
		</div>
	</fieldset>
<?php echo '<?php'; ?> echo Form::close(); ?>