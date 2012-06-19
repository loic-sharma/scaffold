<?php

use Laravel\CLI\Command;

class Scaffold_Generate_Task {

	public function run($arguments)
	{
		$count = count($arguments);

		if($count == 0)
		{
			echo 'I need a table name!';
		}

		else
		{
			$table = $arguments[0];

			array_shift($arguments);

			// Build an array of the fields.
			$fields = array();

			$timestamps = false;

			foreach($arguments as $argument)
			{
				if($argument == 'timestamps')
				{
					$timestamps = true;
				}

				else
				{
					list($field, $type) = explode(':', $argument);

					$fields[$field] = $type;
				}
			}

			if($count > 1)
			{
				echo 'Created migration: '.path('app').'models'.DS.$table.'.php'.PHP_EOL;

				$this->create_migration($table, $fields, $timestamps);
			}

			echo 'Created controller: '.path('app').'controllers'.DS.$table.'.php'.PHP_EOL;

			$this->create_controller($table, $fields);

			echo 'Created view: '.path('app').'views'.DS.$table.DS.'index.php'.PHP_EOL;
			echo 'Created view: '.path('app').'views'.DS.$table.DS.'create.php'.PHP_EOL;
			echo 'Created view: '.path('app').'views'.DS.$table.DS.'edit.php'.PHP_EOL;
		}
	}

	public function create_migration($table, $fields, $timestamps)
	{
		// We won't need the singular form of the table's name,
		// so it'll be automatically pluralized.
		$table = Str::plural($table);

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

		$file = $path.$prefix.'_create_'.$table.'_table'.EXT;

		// Generate the migration.
		ob_start();

		include Bundle::path('scaffold').'views'.DS.'templates'.DS.'migration.php';

		$migration = ob_get_clean();

		File::put($file, $migration);

		$this->run_command('migrate');
	}

	public function create_controller($table, $fields)
	{
		$singular = $table;
		$plural   = Str::plural($singular);
		$model    = Str::classify($singular);

		ob_start();

		include Bundle::path('scaffold').'views'.DS.'templates'.DS.'controller.php';

		$controller = ob_get_clean();

		$file = path('app').'controllers'.DS.$plural.'.php';

		File::put($file, $controller);
	}

	public function run_command($command)
	{
		ob_start();

		Command::run((array)$command);

		ob_end_clean();
	}
}