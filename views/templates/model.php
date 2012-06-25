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
<?php foreach($relationships as $relationship => $models): ?>
<?php foreach($models as $model): ?>

<?php if(strpos($relationship, 'has_many') === 0): ?>
	/**
	 * Establish the relationship between a <?php echo $singular; ?> and <?php echo str_replace('_', ' ', Str::plural($model)); ?>.
	 *
	 * @return Laravel\Database\Eloquent\Relationships\<?php echo Str::classify($relationship).PHP_EOL; ?>
	 */
	public function <?php echo Str::plural($model); ?>()
<?php else: ?>
	/**
	 * Establish the relationship between a <?php echo $singular; ?> and a <?php echo str_replace('_', ' ', $model); ?>.
	 *
	 * @return Laravel\Database\Eloquent\Relationships\<?php echo Str::classify($relationship).PHP_EOL; ?>
	 */
	public function <?php echo $model; ?>()
<?php endif; ?>
	{
		return $this-><?php echo $relationship; ?>('<?php echo Str::classify($model); ?>');
	}
<?php endforeach; ?>
<?php endforeach; ?>
}