<?php namespace Scaffold\Drivers;

use PDO;

class SQLite extends Driver {

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
		$fields = array();

		try
		{
			$columns = $this->pdo->query("pragma table_info($table)")->fetchAll(PDO::FETCH_ASSOC);

			foreach($columns as $column)
			{
				$fields[] = $column['name'];
			}
		}
		catch (Exception $e)
		{
			throw new Exception($e->errorInfo[2]);
		}

		return $fields;
	}
}