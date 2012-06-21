<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $this->plural_class; ?>_Controller extends Controller {

	public $restful = true;

	public function get_index()
	{
		$rows = <?php echo $this->singular_class; ?>::all();

		return View::make('<?php echo $this->plural; ?>::index')->with('rows', $rows);
	}
<?php if(isset($this->fields['id'])): ?>

	public function get_add()
	{
		$<?php echo $this->singular; ?> = new <?php echo $this->singular_class; ?>;

		return View::make('<?php echo $this->plural; ?>::add');
	}

	public function get_edit($id)
	{
		return View::make('<?php echo $this->plural; ?>::edit');
	}

	public function get_delete($id)
	{
		<?php echo $this->singular_class; ?>::where_id($id)->delete();
	}
<?php endif; ?>
}