<?php echo '<?php'.PHP_EOL; ?>

class <?php echo Str::classify($plural); ?>_Controller extends Controller {

	public $restful = true;

	public function get_index()
	{
		$rows = <?php echo $model; ?>::all();

		return View::make('<?php echo $plural; ?>::index', compact('rows'));
	}
<?php if(isset($fields['id'])): ?>

	public function get_add()
	{
		$<?php echo $singular; ?> = new <?php echo $model; ?>;

		return View::make('<?php echo $plural; ?>::add');
	}

	public function get_edit($id)
	{
		return View::make('<?php echo $plural; ?>::edit');
	}

	public function get_delete($id)
	{
		<?php echo $model; ?>::where_id($id)->delete();
	}
<?php endif; ?>
}