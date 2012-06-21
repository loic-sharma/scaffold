<?php

use Laravel\CLI\Command;

class Scaffold_Make_Task {

	public $singular;
	public $singular_class;
	public $plural;
	public $plural_class;

	public $fields = array();
	public $timestamps = false;

	public function run($arguments)
	{
		$count = count($arguments);

		if($count == 0)
		{
			echo 'I need a table name!';
		}

		else
		{
			$this->singular = array_shift($arguments);
			$this->plural = Str::plural($this->singular);

			$this->singular_class = Str::classify($this->singular);
			$this->plural_class = Str::classify($this->plural);

			// If there was more than one argument passed, the user wishes
			// to create a new table and has listed out each of the fields.
			if($count > 1)
			{
				// Build an array of the fields.
				foreach($arguments as $argument)
				{
					if($argument == 'timestamps')
					{
						$this->timestamps = true;
					}

					else
					{
						list($field, $type) = explode(':', $argument);

						$this->fields[$field] = $type;
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

			$this->create_model();
			$this->create_controller();

			$this->create_view('layout');
			$this->create_view('index');
			$this->create_view('view');
			$this->create_view('create');
			$this->create_view('edit');
		}
	}

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

		$file = $path.$prefix.'_create_'.$this->plural.'_table'.EXT;

		// Generate the migration.
		ob_start();

		include Bundle::path('scaffold').'views'.DS.'templates'.DS.'migration.php';

		$migration = ob_get_clean();

		File::put($file, $migration);

		echo 'Created migration: '.$file.PHP_EOL;
	}

	public function create_model()
	{
		ob_start();

		include Bundle::path('scaffold').'views'.DS.'templates'.DS.'model.php';

		$model = ob_get_clean();

		$file = path('app').'models'.DS.$this->singular.'.php';

		File::put($file, $model);

		echo 'Created model: '.$file.PHP_EOL;
	}

	public function create_controller()
	{
		ob_start();

		include Bundle::path('scaffold').'views'.DS.'templates'.DS.'controller.php';

		$controller = ob_get_clean();

		$file = path('app').'controllers'.DS.$this->plural.'.php';

		File::put($file, $controller);

		echo 'Created controller: '.$file.PHP_EOL;
	}

	public function create_view($view)
	{
		ob_start();

		include Bundle::path('scaffold').'views'.DS.'templates'.DS.'views'.DS.$view.'.php';

		$content = ob_get_clean();

		$path = path('app').'views'.DS.$this->plural.DS;
		$file = $path.$view.'.php';

		if( ! is_dir($path))
		{
			mkdir($path);
		}

		File::put($file, $content);

		echo 'Created view: '.$file.PHP_EOL;
	}

	public function run_command($command)
	{
		ob_start();

		Command::run((array)$command);

		ob_end_clean();
	}
}