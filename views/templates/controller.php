<?php echo '<?php'.PHP_EOL; ?>

class <?php echo $plural_class; ?>_Controller extends Controller {

	/**
	 * The layout being used by the controller.
	 *
	 * @var string
	 */
	public $layout = 'layouts.scaffold';

	/**
	 * Indicates if the controller uses RESTful routing.
	 *
	 * @var bool
	 */
	public $restful = true;

	/**
	 * View all of the <?php echo $plural; ?>.
	 *
	 * @return void
	 */
	public function get_index()
	{
<?php if($has_relationships): ?>
		$<?php echo $plural; ?> = <?php echo $singular_class; ?>::with(array(<?php echo $with; ?>))->get();
<?php else: ?>
		$<?php echo $plural; ?> = <?php echo $singular_class; ?>::all();
<?php endif; ?>

		$this->layout->title   = '<?php echo ucwords(str_replace('_', ' ', $plural_class)); ?>';
		$this->layout->content = View::make('<?php echo $plural; ?>.index')->with('<?php echo $plural; ?>', $<?php echo $plural; ?>);
	}

	/**
	 * Show the form to create a new <?php echo $singular; ?>.
	 *
	 * @return void
	 */
	public function get_create(<?php echo $belongs_to_params; ?>)
	{
		$this->layout->title   = 'New <?php echo str_replace('_', ' ', $singular_class); ?>';
<?php if(count($belongs_to) == 0): ?>
		$this->layout->content = View::make('<?php echo $plural; ?>.create');
<?php else: ?>
		$this->layout->content = View::make('<?php echo $plural; ?>.create', array(
<?php foreach($belongs_to as $model): ?>
									'<?php echo $model; ?>_id' => $<?php echo $model; ?>_id,
<?php endforeach; ?>
								));
<?php endif; ?>
	}

	/**
	 * Create a new <?php echo $singular; ?>.
	 *
	 * @return Response
	 */
	public function post_create()
	{
		$validation = Validator::make(Input::all(), array(
<?php foreach($fields as $field => $type): ?>
			'<?php echo $field; ?>' => array(<?php if($type == 'boolean'): ?>
'in:0,1'<?php elseif($type == 'string'): ?>
'required'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php elseif($type == 'integer'): ?>
'required', 'integer'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php elseif($type == 'float'): ?>
'required', 'numeric'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php else: ?>
'required'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php endif; ?>),
<?php endforeach; ?>
		));

		if($validation->valid())
		{
			$<?php echo $singular; ?> = new <?php echo $singular_class; ?>;

<?php foreach($fields as $field => $type): ?>
<?php if($type != 'boolean'): ?>
			$<?php echo $singular; ?>-><?php echo $field; ?> = Input::get('<?php echo $field; ?>');
<?php else: ?>
			$<?php echo $singular; ?>-><?php echo $field; ?> = Input::get('<?php echo $field; ?>', '0');
<?php endif; ?>
<?php endforeach; ?>

			$<?php echo $singular; ?>->save();

			Session::flash('message', 'Added <?php echo str_replace('_', ' ', $singular); ?> #'.$<?php echo $singular; ?>->id);

			return Redirect::to('<?php echo $plural; ?>');
		}

		else
		{
			return Redirect::to('<?php echo $plural; ?>/create')->with_errors($validation->errors);
		}
	}

	/**
	 * View a specific <?php echo $singular; ?>.
	 *
	 * @param  int   $id
	 * @return void
	 */
	public function get_view($id)
	{
<?php if($has_relationships): ?>
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::with(array(<?php echo $with; ?>))->find($id);
<?php else: ?>
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);
<?php endif; ?>

		if(is_null($<?php echo $singular; ?>))
		{
			return Redirect::to('<?php echo $plural; ?>');
		}

		$this->layout->title   = 'Viewing <?php echo str_replace('_', ' ', $singular_class); ?> #'.$id;
		$this->layout->content = View::make('<?php echo $plural; ?>.view')->with('<?php echo $singular; ?>', $<?php echo $singular; ?>);
	}

	/**
	 * Show the form to edit a specific <?php echo $singular; ?>.
	 *
	 * @param  int   $id
	 * @return void
	 */
	public function get_edit($id)
	{
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);

		if(is_null($<?php echo $singular; ?>))
		{
			return Redirect::to('<?php echo $plural; ?>');
		}

		$this->layout->title   = 'Editing <?php echo str_replace('_', ' ', $singular_class); ?>';
		$this->layout->content = View::make('<?php echo $plural; ?>.edit')->with('<?php echo $singular; ?>', $<?php echo $singular; ?>);
	}

	/**
	 * Edit a specific <?php echo $singular; ?>.
	 *
	 * @param  int       $id
	 * @return Response
	 */
	public function post_edit($id)
	{
		$validation = Validator::make(Input::all(), array(
<?php foreach($fields as $field => $type): ?>
			'<?php echo $field; ?>' => array(<?php if($type == 'boolean'): ?>
'in:0,1'<?php elseif($type == 'string'): ?>
'required'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php elseif($type == 'integer'): ?>
'required', 'integer'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php elseif($type == 'float'): ?>
'required', 'numeric'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php else: ?>
'required'<?php if(isset($size[$field])): ?>, 'max:<?php echo $size[$field]; ?>'<?php endif; ?><?php endif; ?>),
<?php endforeach; ?>
		));

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

			Session::flash('message', 'Updated <?php echo str_replace('_', ' ', $singular); ?> #'.$<?php echo $singular; ?>->id);

			return Redirect::to('<?php echo $plural; ?>');
		}

		else
		{
			return Redirect::to('<?php echo $plural; ?>/edit/'.$id)->with_errors($validation->errors);
		}
	}

	/**
	 * Delete a specific <?php echo $singular; ?>.
	 *
	 * @param  int       $id
	 * @return Response
	 */
	public function get_delete($id)
	{
		$<?php echo $singular; ?> = <?php echo $singular_class; ?>::find($id);

		if( ! is_null($<?php echo $singular; ?>))
		{
			$<?php echo $singular; ?>->delete();

			Session::flash('message', 'Deleted <?php echo str_replace('_', ' ', $singular); ?> #'.$<?php echo $singular; ?>->id);
		}

		return Redirect::to('<?php echo $plural; ?>');
	}
}