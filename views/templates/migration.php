<?php echo '<?php'.PHP_EOL; ?>

class Create_<?php echo Str::classify($table); ?>_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{	
		Schema::create('<?php echo $table; ?>', function($table)
		{
<?php foreach($fields as $field => $type): ?>
			$table-><?php echo $type; ?>('<?php echo $field; ?>');
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
		Schema::drop('<?php echo $table; ?>');
	}

}