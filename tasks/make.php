<?php

use Laravel\CLI\Command;

class Scaffold_Make_Task {

	/**
	 * The table's data.
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * The type of parser the views will use.
	 *
	 * @var string
	 */
	public $parser;

	/**
	 * The list of different relationships.
	 *
	 * @var array
	 */
	public $relationships = array(
		'has_one',
		'has_one_or_many',
		'belongs_to',
		'has_many',
		'has_many_and_belongs_to',
	);

	/**
	 * Is the scaffold generator in testing mode?
	 *
	 * @var bool
	 */
	public $testing = false;

	/**
	 * Display a message if testing is turned on.
	 *
	 * @return void
	 */
	public function __construct()
	{
		if($this->testing)
		{
			$this->log('This bundle is in testing mode!');
		}
	}

	/**
	 * Create a new scaffold.
	 *
	 * @param  array  $arguments
	 * @return void
	 */
	public function run($arguments)
	{
		$this->parser = Config::get('scaffold::scaffold.parser');

		$count = count($arguments);

		if($count == 0)
		{
			echo 'I need a table name!';
		}

		else
		{
			$this->data['timestamps'] = false;

			$this->data['singular'] = array_shift($arguments);
			$this->data['plural'] = Str::plural($this->data['singular']);

			$this->data['singular_class'] = Str::classify($this->data['singular']);
			$this->data['plural_class'] = Str::classify($this->data['plural']);

			// Each relationship should have at least an empty array to make sure
			// no errors occur in the template.
			foreach($this->relationships as $relationship)
			{
				$this->data['relationships'][$relationship] = array();
			}

			// All models start out antisocial.
			$this->data['has_relationships'] = false;

			$this->data['fields'] = array();

			// If there was more than one argument passed, the user wishes
			// to create a new table and has listed out each of the fields.
			if($count > 1)
			{
				// Build an array of the fields.
				foreach($arguments as $argument)
				{
					if($argument == 'timestamps')
					{
						$this->data['timestamps'] = true;
					}

					else
					{
						// Each argument contains data concatenated by a semi-colon.
						$pieces = explode(':', $argument);

						// Determine if the user wishes to define a new
						// relationship.
						if(in_array($pieces[0], $this->relationships))
						{
							$relationship = $pieces[0];

							// That's my boy!
							$this->data['has_relationships'] = true;

							$this->data['relationships'][$relationship] = explode(',', $pieces[1]);
						}

						// If the user is not defining a new relationship, then
						// the user is creating a new field.
						else
						{
							$field = $pieces[0];

							// The second piece is the field's type.
							$this->data['fields'][$field] = $pieces[1];
							$this->data['nullable'][$field] = in_array('nullable', $pieces);

							// Don't set a size unless one was given.
							if( isset($pieces[2]) && is_numeric($pieces[2]))
							{
								$this->data['size'][$field] = $pieces[2];
							}
						}
					}
				}

				$this->prepare_relationships();
				$this->create_migration();
			}

			// If only the table's name was passed, the user expects the
			// scaffolding to use an already existing table. 
			else
			{
				echo 'Generating scaffolding for an already existing table isn\'t implemented yet.';

				return;
			}

			$this->create_controller();
			$this->create_model();

			$this->create_view('layout');
			$this->create_view('index');
			$this->create_view('view');
			$this->create_view('create');
			$this->create_view('edit');
		}
	}

	/**
	 * Prepare the relationship view data.
	 *
	 * @return void
	 */
	public function prepare_relationships()
	{	
		// Just for the sake of simplicity in the templates, we will manually
		// separate the relationships based on plurality.
		$this->data['single_relationships'] = array(
			'has_one'    => $this->data['relationships']['has_one'],
			'belongs_to' => $this->data['relationships']['belongs_to'],
		);

		// This model has some serious game!
		$this->data['plural_relationships'] = array(
			'has_one_or_many'         => $this->data['relationships']['has_one_or_many'],
			'has_many'                => $this->data['relationships']['has_many'],
			'has_many_and_belongs_to' => $this->data['relationships']['has_many_and_belongs_to'],
		);

		// If the table has relationships, each route in the controller
		// will need to load these relationships. Let's build a list of
		// those pesky models here. Also, relationships where the model
		// belongs to another  model will need a field to link the two
		// models together.
		$this->data['with'] = '';

		$this->data['belongs_to'] = array();
		$this->data['belongs_to_params'] = '';

		if($this->data['has_relationships'])
		{
			$belongs_to_relationships = array('belongs_to', 'has_many_and_belongs_to');
			$plural_relationships = array_keys($this->data['plural_relationships']);

			foreach($this->data['relationships'] as $relationship => $models)
			{
				foreach($models as $model)
				{
					// The plural relationships will need the model's
					// name to be pluralized.
					if(in_array($relationship, $plural_relationships))
					{
						$this->data['with'] .= "'".Str::plural($model)."', ";								
					}

					else
					{
						$this->data['with'] .= "'{$model}', ";
					}

					// Let's add the field to link belongs_to relationships.
					if(in_array($relationship, $belongs_to_relationships))
					{
						$field = array($model.'_id' => 'integer');

						// We use array_merge to add the new field to the
						// beginning of the associative array of fields.
						$this->data['fields'] = array_merge($field, $this->data['fields']);

						// Lastly, we'll save this model just for convenience
						// when generating the controller.
						$this->data['belongs_to'][] = $model;

						$this->data['belongs_to_params'] .= '$'.$model.'_id = null, ';
					}
				}
			}

			// The last two characters needs to be removed to get rid
			// of that last comma and space.
			$this->data['with'] = substr($this->data['with'], 0, -2);

			$this->data['belongs_to_params'] = substr($this->data['belongs_to_params'], 0, -2);
		}
	}

	/**
	 * Create a new migration.
	 *
	 * @return void
	 */
	public function create_migration()
	{
		// The migration path is prefixed with the date timestamp, which
		// is a better way of ordering migrations than a simple integer
		// incrementation, since developers may start working on the
		// next migration at the same time unknowingly.
		$prefix = ( ! $this->testing) ? date('Y_m_d_His') : '0000_00_00_000000';

		$path = path('app').'migrations'.DS;

		// If the migration directory does not exist for the bundle,
		// we will create the directory so there aren't errors when
		// when we try to write the migration file.
		if ( ! is_dir($path)) mkdir($path);

		$file = $path.$prefix.'_create_'.$this->data['plural'].'_table'.EXT;

		// Generate the migration.
		$migration = View::make('scaffold::migration', $this->data)->render();

		File::put($file, $migration);

		$this->log('created migration: '.$file);
	}

	/**
	 * Create a new model.
	 *
	 * @return void
	 */
	public function create_model()
	{
		$model = View::make('scaffold::model', $this->data)->render();

		// Laravel's autoloader will search within nested directories if the
		// model name contains underscores. Just in case some developer
		// likes long table names, let's make sure that this will still work.
		$path = path('app').'models';

		if(strpos($this->data['singular'], '_'))
		{
			// Get all of the directories the model needs to be nested in, and
			// plop them to the end of the path.
			$pieces = explode('_', $this->data['singular']);
			$count  = count($pieces);

			for($i = 0; $i < $count; $i++)
			{
				$path .= DS.$pieces[$i];

				// All of the pieces except for the very last one are directories
				// which may not already exist. To prevent any issues from occuring,
				// they will be created.
				if(($i + 1) != $count)
				{
					if( ! is_dir($path)) mkdir($path);
				}
			}

			// The last piece was the actual file's name, so only the extension
			// is missing now.
			$file = $path.EXT;
		}

		else
		{
			$file = $path.DS.$this->data['singular'].EXT;
		}

		File::put($file, $model);

		$this->log('Created model: '.$file);
	}

	/**
	 * Create a new controller.
	 *
	 * @return void
	 */
	public function create_controller()
	{
		$controller = View::make('scaffold::controller', $this->data)->render();

		$file = path('app').'controllers'.DS.$this->data['plural'].EXT;

		File::put($file, $controller);

		$this->log('Created controller: '.$file);
	}

	/**
	 * Create a new view.
	 *
	 * @param  string  $view
	 * @return void
	 */
	public function create_view($view)
	{
		$content = View::make('scaffold::'.$this->parser.'.'.$view, $this->data)->render();

		// The layout view is special. Unlike all the other views, this one is
		// placed in the layout directory.
		if($view == 'layout')
		{
			$path = path('app').'views'.DS.'layouts'.DS;

			// The name of the layout has to be changed to 'scaffold' to ensure
			// there will not be any conflicts.
			$view = 'scaffold';
		}

		// All the other views are placed in a directory named after the
		// table.
		else
		{
			$path = path('app').'views'.DS.$this->data['plural'].DS;
		}

		// If the view directory for this table does not exist, it will
		// need to be created before any files are created.
		if ( ! is_dir($path)) mkdir($path);

		if($this->parser == 'blade')
		{
			$extension = BLADE_EXT;
		}

		else
		{
			$extension = EXT;
		}

		$file = $path.$view.$extension;

		File::put($file, $content);

		$this->log('Created view: '.$file);
	}

	/**
	 * Show a message on the CLI.
	 *
	 * @param  string  $message
	 * @return void
	 */
	public function log($message)
	{
		echo "\t".str_replace(path('base'), '', $message).PHP_EOL;
	}
}