<?php namespace crud\DBUtil\Drivers;

use Laravel\Database as DB;

abstract class Driver {

	public $pdo;

	public function __construct()
	{
		$this->pdo = DB::connection()->pdo;
	}

	abstract public function tables();

	abstract public function fields($table);
}