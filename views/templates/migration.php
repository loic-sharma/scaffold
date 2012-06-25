<?php echo '<?php'.PHP_EOL; ?>

class Create_<?php echo $plural_class; ?>_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{	
		Schema::create('<?php echo $plural; ?>', function($table)
		{
			$table->increments('id');

<?php foreach($fields as $field => $type): ?>
			$table-><?php echo $type ?>('<?php echo $field; ?>'<?php if(isset($size[$field])) echo ', '.$size[$field]; ?>)<?php if (isset($nullable[$field]) && $nullable[$field]) echo '->nullable()'; ?>;
<?php endforeach; ?>
<?php if($timestamps): ?>

			$table->timestamps();
<?php endif; ?>
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('<?php echo $plural; ?>');
	}

}