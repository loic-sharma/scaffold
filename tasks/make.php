<?php

use Laravel\CLI\Command;

class Scaffold_Make_Task {

	/**
	 * Is the scaffold generator in testing mode?
	 *
	 * @var bool
	 */
	public $testing;

	/**
	 * Wether or not to run migrations after a new scaffold is created.
	 *
	 * @var bool
	 */
	public $migrate;

	/**
	 * The type of parser the views will use.
	 *
	 * @var string
	 */
	public $parser;

	/**
	 * View extensions.
	 *
	 * @var array
	 */
	public $extensions;

	/**
	 * The table's data.
	 *
	 * @var array
	 */
	public $data = array();

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
	 * Create a new scaffold.
	 *
	 * @param  array  $arguments
	 * @return void
	 */
	public function run($arguments)
	{
		$this->testing    = Config::get('scaffold::scaffold.testing');
		$this->migrate    = Config::get('scaffold::scaffold.migrate');
		$this->parser     = Config::get('scaffold::scaffold.parser');
		$this->extensions = Config::get('scaffold::scaffold.extensions');

		if($this->testing)
		{
			$this->log('This bundle is in testing mode!');
		}

		$count = count($arguments);

		// If there are zero arguments, we were given absolutely nothing.
		if($count == 0)
		{
			$this->log('I need a table name!');
		}

		// If there is one argument, only the scaffold's name was passed. This
		// task requires attributes as well.
		elseif($count == 1)
		{
			$this->log('The scaffold must have attributes!');
		}

		else
		{
			// Let's prepare all of the default data to prevent any errors
			// in the generated files.
			$this->data['singular'] = array_shift($arguments);

			// If there is a period in the scaffold's name the scaffold
			// must be nested. Let's take care of that now.
			$this->data['nested_pieces'] = array();
			$this->data['nested_prefix'] = '';
			$this->data['nested_path']   = '';
			$this->data['nested_view']   = '';

			if(strpos($this->data['singular'], '.') !== false)
			{
				$this->data['nested_pieces'] = explode('.', $this->data['singular']);

				// The last piece is actually the scaffold's name, so let's
				// remove that.
				$this->data['singular'] = array_pop($this->data['nested_pieces']);

				$this->data['nested_prefix'] = implode('_', $this->data['nested_pieces']).'_';
				$this->data['nested_path']   = implode('/', $this->data['nested_pieces']).'/';
				$this->data['nested_view']   = implode('.', $this->data['nested_pieces']).'.';
			}

			$this->data['plural'] = Str::plural($this->data['singular']);

			// The classes must be prefixed if they are nested.
			$this->data['singular_class'] = $this->data['nested_prefix'].$this->data['singular'];
			$this->data['singular_class'] = Str::classify($this->data['singular_class']);

			$this->data['plural_class'] = $this->data['nested_prefix'].$this->data['plural'];
			$this->data['plural_class'] = Str::classify($this->data['plural_class']);

			// Let's also set the table name for this scaffold.
			$this->data['table_name'] = $this->data['nested_prefix'].$this->data['plural'];

			foreach($this->relationships as $relationship)
			{
				$this->data['relationships'][$relationship] = array();
			}

			$this->data['has_relationships'] = false;

			// Build an array of the fields.
			$this->data['timestamps'] = false;
			$this->data['fields'] = array();
			$this->data['url']    = array();

			foreach($arguments as $argument)
			{
				$this->extract_argument_data($argument);
			}

			$this->prepare_relationships();
			$this->create_migrations();

			$this->create_controller();
			$this->create_model();

			$this->create_view('layout');
			$this->create_view('index');
			$this->create_view('view');
			$this->create_view('create');
			$this->create_view('edit');

			if($this->migrate)
			{
				$this->run_migrations();
			}
		}
	}

	/**
	 * Extract the data from an argument.
	 *
	 * @param  string  $argument
	 * @return void
	 */
	public function extract_argument_data($argument)
	{
		if($argument == 'timestamps')
		{
			$this->data['timestamps'] = true;
		}

		else
		{
			// The timestamps argument is the only argument that should not
			// have a colon. Let's just make sure the argument is valid just
			// to be safe.
			if(strpos($argument, ':') !== false)
			{
				$pieces = explode(':', $argument);

				// Determine if the argument defines a relationship.
				if(in_array($pieces[0], $this->relationships))
				{
					$this->data['has_relationships'] = true;

					// The first piece is the type of the relationship.
					$relationship = $pieces[0];

					// The second piece contains all of the models that are
					// part of this relationship, separated by a comma.
					$relationships = explode(',', $pieces[1]);

					foreach($relationships as $key => $value)
					{
						$url = $value;

						// Let's handle nested relationships now.
						if(strpos($value, '.') !== false)
						{
							$url   = str_replace('.', '/', $value);
							$value = str_replace('.', '_', $value);

							$relationships[$key] = $value;
						}

						// The URL should always be plural.
						$url = Str::plural($url);

						$this->data['url'][$relationships[$key]] = $url;
					}

					$this->data['relationships'][$relationship] = $relationships;
				}

				// If the argument is not defining a new relationship, it must
				// then be adding a new attribute to the scaffold.
				else
				{
					// The first piece is the field's name.
					$field = $pieces[0];

					// The second piece is the field's type.
					$this->data['fields'][$field] = strtolower($pieces[1]);
					$this->data['nullable'][$field] = in_array('nullable', $pieces);

					// Don't set a size unless one was given.
					if(isset($pieces[2]) and is_numeric($pieces[2]))
					{
						$this->data['size'][$field] = $pieces[2];
					}
				}
			}
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

		$this->data['plural_relationships'] = array(
			'has_one_or_many'         => $this->data['relationships']['has_one_or_many'],
			'has_many'                => $this->data['relationships']['has_many'],
			'has_many_and_belongs_to' => $this->data['relationships']['has_many_and_belongs_to'],
		);

		// If the table has relationships, each route in the controller
		// will need to load these relationships. Let's build a list of
		// those pesky models here. Also, relationships where the model
		// belongs to another model will need a field to link the two
		// models together.
		$this->data['with'] = '';

		$this->data['belongs_to'] = array();
		$this->data['belongs_to_params'] = '';

		if($this->data['has_relationships'])
		{
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
					if($relationship == 'belongs_to')
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

			// The last two characters needs to be removed to get rid of the
			// last comma and space.
			$this->data['with'] = substr($this->data['with'], 0, -2);

			$this->data['belongs_to_params'] = substr($this->data['belongs_to_params'], 0, -2);
		}
	}

	/**
	 * Create the scaffold's migrations.
	 *
	 * @return void
	 */
	public function create_migrations()
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

		// Let's start adding in the migrations that will need to be
		// created. Usually, we'll just need the scaffold's table migration. 
		$migrations = array(
			array(
				'name' => 'create_'.$this->data['nested_prefix'].$this->data['plural'].'_table',
				'data' => $this->data,
			)
		);

		// However, if there are any many-to-many relationships we will
		// need to create pivot tables. Let's add those in.
		foreach($this->data['relationships']['has_many_and_belongs_to'] as $relationship)
		{
			if(strpos($relationship, '.') !== false)
			{
				$relationship = str_replace('.', '_', $relationship);
			}

			// The pivot requires the two tables to be sorted
			// alphabetically.
			$tables = array($this->data['nested_prefix'].$this->data['singular'], $relationship);

			sort($tables);

			$pivot = implode('_', $tables);

			$migrations[] = array(
				'name' => 'create_'.$pivot.'_table',
				'data' => array(
					'nested_prefix' => $this->data['nested_prefix'],
					'plural'        => $pivot,
					'plural_class'  => Str::classify($pivot),
					'table_name'    => $pivot,
					'fields'        => array(
						$this->data['nested_prefix'].$this->data['singular'].'_id' => 'integer',
						$relationship.'_id' => 'integer',
					),
					'timestamps'    => true,
				),
			);
		}

		// Let's now loop through each migration and create them.
		foreach($migrations as $migration)
		{
			$file = $path.$prefix.'_'.$migration['name'].EXT;

			// Generate the migration.
			File::put($file, View::make('scaffold::migration', $migration['data'])->render());

			$this->log('Created migration: '.$migration['name']);
		}
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

		// We use the class name as only that is prefixed if the scaffold is nested.
		if(strpos($this->data['singular_class'], '_'))
		{
			// Get all of the directories the model needs to be nested in, and
			// plop them to the end of the path.
			$pieces = explode('_', $this->data['singular_class']);
			$count  = count($pieces);

			for($i = 0; $i < $count; $i++)
			{
				$path .= DS.strtolower($pieces[$i]);

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
		// Let's find out if the app has a base controller.
		$this->detect_controller();

		$controller = View::make('scaffold::controller', $this->data)->render();

		$path = path('app').'controllers'.DS.$this->data['nested_path'];

		if( ! is_dir($path)) mkdir($path, 0777, true);

		$file = $path.$this->data['plural'].EXT;

		File::put($file, $controller);

		$this->log('Created controller: '.$file);
	}

	/**
	 * Detect the controller the scaffold should inherit.
	 *
	 * @return void
	 */
	public function detect_controller()
	{
		// We'll just use the Controller class by default.
		$this->data['controller'] = 'Controller';

		if(file_exists(path('app').'controllers'.DS.'base'.EXT))
		{
			$this->data['controller'] = 'Base_Controller';
		}
	}

	/**
	 * Create a new view.
	 *
	 * @param  string  $view
	 * @return void
	 */
	public function create_view($view)
	{
		try
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
				$path = path('app').'views'.DS;

				if( ! empty($this->data['nested_path']))
				{
					foreach($this->data['nested_pieces'] as $piece)
					{
						$path .= $piece.DS;

						// If the view directory for the nested path does not
						// exist, it will need to be created.
						if( ! is_dir($path)) mkdir($path);
					}
				}

				$path .= $this->data['plural'].DS;
			}

			// Create the final directory it it doesn't already exist.
			if ( ! is_dir($path)) mkdir($path);

			if(isset($this->extensions[$this->parser]))
			{
				$extension = $this->extensions[$this->parser];
			}

			else
			{
				$extension = $this->extensions['default'];
			}

			$file = $path.$view.$extension;

			File::put($file, $content);

			$this->log('Created view: '.$file);
		}

		// If an exception is thrown, the parser set in the config file does
		// not exist.
		catch(Exception $e)
		{
			$this->log($e->getMessage());

			exit;
		}
	}

	/**
	 * Run the new migrations.
	 *
	 * @return void
	 */
	public function run_migrations()
	{
		ob_start();

		Laravel\CLI\Command::run(array('migrate'));

		$this->log(ob_get_clean());
	}

	/**
	 * Show a message on the CLI.
	 *
	 * @param  string  $message
	 * @return void
	 */
	public function log($message)
	{
		// Let's prettify the message
		$message = str_replace(array(path('base'), '/'), array('', DS), $message);

		echo '  '.$message.PHP_EOL;
	}
}