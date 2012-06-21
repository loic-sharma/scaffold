<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $this->singular_class; ?> extends Eloquent {

	public static $table = '<?php echo $this->plural; ?>';
	public static $timestamps = <?php echo ($this->timestamps) ? 'true' : 'false'; ?>;
}