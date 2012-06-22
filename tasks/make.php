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
	 * Create a new scaffold.
	 *
	 * @param  array  $arguments
	 * @return void
	 */
	public function run($arguments)
	{
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
						list($field, $type) = explode(':', $argument);

						$this->data['fields'][$field] = $type;
					}
				}

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
		$prefix = date('Y_m_d_His');

		$path = path('app').'migrations'.DS;

		// If the migration directory does not exist for the bundle,
		// we will create the directory so there aren't errors when
		// when we try to write the migration file.
		if ( ! is_dir($path)) mkdir($path);

		$file = $path.$prefix.'_create_'.$this->data['plural'].'_table'.EXT;

		// Generate the migration.
		$migration = View::make('scaffold::templates.migration', $this->data)->render();

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
		$model = View::make('scaffold::templates.model', $this->data)->render();

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
		$controller = View::make('scaffold::templates.controller', $this->data)->render();

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
		$content = View::make('scaffold::templates.views.'.$view, $this->data)->render();

		$path = path('app').'views'.DS.$this->data['plural'].DS;

		// If the view directory for this table does not exist, it will
		// need to be created before any files are created.
		if ( ! is_dir($path)) mkdir($path);

		$file = $path.$view.EXT;

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