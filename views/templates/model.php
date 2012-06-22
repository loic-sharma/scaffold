<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $singular_class; ?> extends Eloquent {

	/**
	 * The name of the table associated with the model.
	 *
	 * @var string
	 */
	public static $table = '<?php echo $plural; ?>';

	/**
	 * Indicates if the model has update and creation timestamps.
	 *
	 * @var bool
	 */
	public static $timestamps = <?php echo ($timestamps) ? 'true' : 'false'; ?>;
}