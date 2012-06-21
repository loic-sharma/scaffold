<div class="span12">
	<ul class="breadcrumb span6">
		<li>
			<a href="<?php echo '<?php'; ?> echo URL::to('<?php echo $this->plural; ?>'); ?>"><?php echo $this->plural_class; ?></a> <span class="divider">/</span>
		</li>
		<li class="active">Editing <?php echo $this->singular_class; ?></li>
	</ul>
</div>

<?php echo '<?php'; ?> echo Form::open(null, 'post', array('class' => 'form-stacked')); ?>
	<fieldset>
<?php foreach($this->fields as $field => $type): ?>
		<div class="clearfix">
			<?php echo '<?php'; ?> echo Form::label('<?php echo $field; ?>', '<?php echo ucfirst($field); ?>'); ?>

			<div class="input">
<?php if(in_array($type, array('string', 'integer', 'float', 'date', 'timestamp'))): ?>
				<?php echo '<?php'; ?> echo Form::text('<?php echo $field; ?>', Input::old('<?php echo $field; ?>', $<?php echo $this->singular; ?>-><?php echo $field; ?>), array('class' => 'span6')); ?>
<?php elseif($type == 'boolean'): ?>
				<?php echo '<?php'; ?> echo Form::checkbox('<?php echo $field; ?>', '1', Input::old('<?php echo $field; ?>', $<?php echo $this->singular; ?>-><?php echo $field; ?>)); ?>
<?php elseif($type == 'text' || $type == 'blob'): ?>
				<?php echo '<?php'; ?> echo Form::textarea('<?php echo $field; ?>', Input::old('<?php echo $field; ?>', $<?php echo $this->singular; ?>-><?php echo $field; ?>), array('class' => 'span10')); ?>
<?php endif; ?>
			</div>
		</div>
<?php endforeach; ?>

		<div class="actions">
			<?php echo '<?php'; ?> echo Form::submit('Save', array('class' => 'btn primary')); ?>
		</div>
	</fieldset>
<?php echo '<?php'; ?> echo Form::close(); ?>