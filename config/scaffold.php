<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Test Mode
	|--------------------------------------------------------------------------
	|
	| While in the test mode file names for migrations will not be
	| prefixed by a date timestamp. This allows for easier migration rollbacks
	| if a change is necessary.
	|
	*/

	'testing' => false,

	/*
	|--------------------------------------------------------------------------
	| Automatically Migrate
	|--------------------------------------------------------------------------
	|
	| After a new scaffold is created, a migration file is created. By
	| default, the new migration will not be migrated. However, you can
	| change this to automatically run the new migration.
	|
	*/

	'migrate' => false,

	/*
	|--------------------------------------------------------------------------
	| View Parser
	|--------------------------------------------------------------------------
	|
	| By default, generated views use the Blade parser. However, you may want
	| to use the PHP parser, or any other custom parser, to match the other
	| views in your application.
	|
	| Parsers: 'blade', 'php'.
	|
	*/

	'parser' => 'blade',

	/*
	|--------------------------------------------------------------------------
	| View Extensions
	|--------------------------------------------------------------------------
	|
	| The generated view extension depends on the view parser. You can change
	| the extension used by a parser, or add the extension of your own parser
	| here. If a parser's extension is not set the default extension will be
	| used.
	|
	*/
	
	'extensions' => array(

		'blade'   => BLADE_EXT,
		'default' => EXT,
	),

);