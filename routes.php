<?php

Route::get('scaffold', function()
{
	$tables = crud\DBUtil::tables();

	foreach($tables as $key => $table)
	{
		$tables[$key] = (object) array(
			'name'  => $table,
			'items' => DB::table($table)->count()
		);
	}

	return View::make('scaffold::index')->with('tables', $tables);
});

Route::get('scaffold/table/(:any)', function($table)
{
	$fields = crud\DBUtil::fields($table);

	$rows = DB::table($table)->get();

	$editable = false;

	foreach($fields as $field)
	{
		if($field->name == 'id')
		{
			$editable = true;
		}
	}

	return View::make('scaffold::table')
			->with('table', $table)
			->with('fields', $fields)
			->with('rows', $rows)
			->with('editable', $editable);
});

Route::get('scaffold/table/(:any)/edit', function()
{
	$fields = crud\DBUtil::fields($table);

	$rows = DB::table($table)->get();

	$editable = false;

	foreach($fields as $field)
	{
		if($field->name == 'id')
		{
			$editable = true;
		}
	}
});