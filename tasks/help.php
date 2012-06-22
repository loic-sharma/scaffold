<?php

class Scaffold_Help_Task {

	/**
	 * Display the help messages for the scaffold.
	 *
	 * @param  array  $arguments
	 * @return void
	 */
	public function run($arguments)
	{
		echo 'The general command to make a new scaffold is:'.PHP_EOL.PHP_EOL;

		echo 'php artisan scaffold::make <model name> <fields> <timestamps>'.PHP_EOL.PHP_EOL;

		echo 'For example:'.PHP_EOL.PHP_EOL;

		echo 'php artisan scaffold::generate user id:increments username:string'.PHP_EOL;
		echo 'password:string description:text timestamps'.PHP_EOL.PHP_EOL;

		echo 'The model name should be singular. For example, if you wished to generate'.PHP_EOL;
		echo 'the scaffolding for users, the model name would simply be user. Each field'.PHP_EOL;
		echo 'should be separated by a space. The format for a field is:'.PHP_EOL.PHP_EOL;

		echo '<field name>:<field type>'.PHP_EOL.PHP_EOL;

		echo 'If timestamps is added to the end of the command the generated table'.PHP_EOL;
		echo 'will automatically keep track of when rows were inserted and updated.'.PHP_EOL.PHP_EOL;

		echo 'Don\'t forget to run your migrations after generating a new scaffold!';
	}
}