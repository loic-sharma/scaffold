<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $this->plural_class; ?>_Controller extends Controller {

	public $restful = true;
	public $layout = '<?php echo $this->plural; ?>.layout';

	public function get_index()
	{
		$<?php echo $this->plural; ?> = <?php echo $this->singular_class; ?>::all();

		$this->layout->title   = '<?php echo $this->plural_class; ?>';
		$this->layout->content = View::make('<?php echo $this->plural; ?>.index')->with('<?php echo $this->plural; ?>', $<?php echo $this->plural; ?>);
	}

	public function get_create()
	{
		$this->layout->title   = 'New <?php echo $this->singular_class; ?>';
		$this->layout->content = View::make('<?php echo $this->plural; ?>.create');
	}

	public function post_create()
	{
		$validation = Validator::make(Input::all(), array());

		if($validation->valid())
		{
			$<?php echo $this->singular; ?> = new <?php echo $this->singular_class; ?>;

<?php foreach($this->fields as $field => $type): ?>
			$<?php echo $this->singular; ?>-><?php echo $field; ?> = Input::get('<?php echo $field; ?>');
<?php endforeach; ?>

			$<?php echo $this->singular; ?>->save();

			return Redirect::to('<?php echo $this->plural; ?>');
		}

		return Redirect::to('<?php echo $this->plural; ?>');
	}

	public function get_view($id)
	{
		$<?php echo $this->singular; ?> = <?php echo $this->singular_class; ?>::find($id);

		if(is_null($<?php echo $this->singular; ?>))
		{
			return Redirect::to('<?php echo $this->plural; ?>');
		}

		$this->layout->title   = 'Viewing <?php echo $this->singular_class; ?> #'.$id;
		$this->layout->content = View::make('<?php echo $this->plural; ?>.view')->with('<?php echo $this->singular; ?>', $<?php echo $this->singular; ?>);
	}

	public function get_edit($id)
	{
		$<?php echo $this->singular; ?> = <?php echo $this->singular_class; ?>::find($id);

		if(is_null($<?php echo $this->singular; ?>))
		{
			return Redirect::to('<?php echo $this->plural; ?>');
		}

		$this->layout->title   = 'Editing <?php echo $this->singular_class; ?>';
		$this->layout->content = View::make('<?php echo $this->plural; ?>.edit')->with('<?php echo $this->singular; ?>', $<?php echo $this->singular; ?>);
	}

	public function post_edit($id)
	{
		$validation = Validator::make(Input::all(), array());

		if($validation->valid())
		{
			$<?php echo $this->singular; ?> = <?php echo $this->singular_class; ?>::find($id);

			if(is_null($<?php echo $this->singular; ?>))
			{
				return Redirect::to('<?php echo $this->plural; ?>');
			}

<?php foreach($this->fields as $field => $type): ?>
			$<?php echo $this->singular; ?>-><?php echo $field; ?> = Input::get('<?php echo $field; ?>');
<?php endforeach; ?>

			$<?php echo $this->singular; ?>->save();

			return Redirect::to('<?php echo $this->plural; ?>');
		}

		return Redirect::to('<?php echo $this->plural; ?>');
	}

	public function get_delete($id)
	{
		$<?php echo $this->singular; ?> = <?php echo $this->singular_class; ?>::find($id);

		if( ! is_null($<?php echo $this->singular; ?>))
		{
			$<?php echo $this->singular; ?>->delete();
		}

		return Redirect::to('<?php echo $this->plural; ?>');
	}
}