<?php namespace Scaffold\Drivers;

use DB;

abstract class Driver {

	/**
	 * The PDO instance.
	 *
	 * @var PDO
	 */
	public $pdo;

	/**
	 * Get the PDO instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->pdo = DB::connection()->pdo;
	}

	/**
	 * List all of the tables in the database.
	 *
	 * @return array
	 */
	abstract public function all();

	/**
	 * List all of the fields of a table in the database.
	 *
	 * @return array
	 */
	abstract public function fields($table);
}