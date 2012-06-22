<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $plural_class; ?>_Controller extends Controller {

	/**
	 * The layout being used by the controller.
	 *
	 * @var string
	 */
	public $layout = '<?php echo $plural; ?>.layout';

	/**
	 * Indicates if the controller uses RESTful routing.
	 *
	 * @var bool
	 */
	public $restful = true;

	public function get_index()
	{
		$<?php echo $plural; ?> = <?php echo $singular_class; ?>::all();

		$this->layout->title   = '<?php echo $plural_class; ?>';
		$this->layout->content = View::make('<?php echo $plural; ?>.index')->with('<?php echo $plural; ?>', $<?php echo $plural; ?>);
	}

	public function get_create()
	{
		$this->layout->title   = 'New <?php echo $singular_class; ?>';
		$this->layout->content = View::make('<?php echo $plural; ?>.create');
	}

	public function post_create()
	{
		$validation = Validator::make(Input::all(), array());

		if($validation->valid())
		{
			$<?php echo $singular; ?> = new <?php echo $singular_class; ?>;

<?php foreach($fields as $field => $type): ?>
			$<?php echo $singular; ?>-><?php echo $field; ?> = Input::get('<?php echo $field; ?>');
<?php endforeach; ?>

			$<?php echo $singular; ?>->save();

			return Redirect::to('<?php echo $plural; ?>');
		}

		return Redirect::to('<?php echo $plural; ?>');
	}

	public function get_view($id)
	{
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);

		if(is_null($<?php echo $singular; ?>))
		{
			return Redirect::to('<?php echo $plural; ?>');
		}

		$this->layout->title   = 'Viewing <?php echo $singular_class; ?> #'.$id;
		$this->layout->content = View::make('<?php echo $plural; ?>.view')->with('<?php echo $singular; ?>', $<?php echo $singular; ?>);
	}

	public function get_edit($id)
	{
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);

		if(is_null($<?php echo $singular; ?>))
		{
			return Redirect::to('<?php echo $plural; ?>');
		}

		$this->layout->title   = 'Editing <?php echo $singular_class; ?>';
		$this->layout->content = View::make('<?php echo $plural; ?>.edit')->with('<?php echo $singular; ?>', $<?php echo $singular; ?>);
	}

	public function post_edit($id)
	{
		$validation = Validator::make(Input::all(), array());

		if($validation->valid())
		{
			$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);

			if(is_null($<?php echo $singular; ?>))
			{
				return Redirect::to('<?php echo $plural; ?>');
			}

<?php foreach($fields as $field => $type): ?>
			$<?php echo $singular; ?>-><?php echo $field; ?> = Input::get('<?php echo $field; ?>');
<?php endforeach; ?>

			$<?php echo $singular; ?>->save();

			return Redirect::to('<?php echo $plural; ?>');
		}

		return Redirect::to('<?php echo $plural; ?>');
	}

	public function get_delete($id)
	{
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);

		if( ! is_null($<?php echo $singular; ?>))
		{
			$<?php echo $singular; ?>->delete();
		}

		return Redirect::to('<?php echo $plural; ?>');
	}
}