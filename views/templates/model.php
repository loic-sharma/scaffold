<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $this->singular_class; ?> extends Eloquent {

	public $table = '<?php echo $this->plural; ?>';
}