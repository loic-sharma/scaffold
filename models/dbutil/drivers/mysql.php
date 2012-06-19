<?php namespace crud\DBUtil\Drivers;

class MySQL extends Driver {

	/**
	 * Get the tables in the database.
	 *
	 * @param  bool   $with_info
	 * @param  bool   $directus_table
	 * @return array
	 */
	public function tables()
	{
		$tables = array();

		$query = $this->pdo->query('SHOW TABLES');

		while($row = $query->fetch())
		{
			$tables[] = $row[0];
		}

		return $tables;
	}

	/**
	 * List the fields in a specific table.
	 *
	 * @param  string  $table
	 * @return array
	 */
	public function fields($table)
	{
		$fields = array();

		$query = $this->pdo->query('SHOW FULL COLUMNS FROM `'.$table.'`');

		while($row = $query->fetch())
		{
			$info = $this->field_info($row['type']);

			$fields[] = (object) array(
				'name'   => $row['field'],
				'type'   => $info[0],
				'length' => $info[1],
			);
		}

		return $fields;
	}

	/**
	 * Determine the field's type and length off of the column information.
	 *
	 * @param  string  $column
	 * @return array
	 */
	public function field_info($column)
	{
		$type   = $column;
		$length = false;

		if(strpos($column, '(') !== false)
		{
			list($type, $length) = explode('(', $column);

			$length = substr($length, 0, -1);
		}

		return array($type, (int)$length);
	}
}