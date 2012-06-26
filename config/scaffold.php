<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connection
	|--------------------------------------------------------------------------
	|
	| The name of the database connection to use when creating the scaffolds.
	| By default, the default database connection is used.
	|
	*/

	'connection' => Config::get('database.default'),

	/*
	|--------------------------------------------------------------------------
	| View Parser
	|--------------------------------------------------------------------------
	|
	| By default, generated views use the Blade parser. However, you may want
	| to use the PHP parser, or any other custom parser to match the other
	| views in your application. 
	|
	*/

	'parser' => 'blade',
);