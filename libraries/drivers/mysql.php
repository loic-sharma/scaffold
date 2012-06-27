<?php namespace Scaffold\Drivers;

use PDO;

class MySQL extends Driver {

	/**
	 * List all of the tables in the database.
	 *
	 * @return array
	 */
	public function all()
	{
		
	}

	/**
	 * List all of the fields of a table in the database.
	 *
	 * @return array
	 */
	public function fields($table)
	{
		$columns = $this->pdo->query("SHOW FULL COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

		$fields = array();

		foreach ($columns as $column)
		{
			$fields[] = $column['field'];
		}

		return $fields;
	}
}