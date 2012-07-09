<?php namespace Scaffold;

use DB;

class Table {

	/**
	 * The current active drivers.
	 *
	 * @var array
	 */
	public static $drivers = array();

	/**
	 * The list of fields of each table.
	 *
	 * @var array
	 */
	public static $fields = array();

	/**
	 * Get the instance of a driver.
	 *
	 * @param  string  $driver
	 * @return Driver
	 */
	public static function instance($driver = null)
	{
		// If no driver is given we will use the driver that the current
		// connection uses.
		if(is_null($driver)) $driver = DB::connection()->config['driver'];

		if( ! isset(static::$drivers[$driver]))
		{
			static::$drivers[$driver] = static::factory($driver);
		}

		return static::$drivers[$driver];
	}

	/**
	 * Create a new instance of a driver.
	 *
	 * @param  string  $driver
	 * @return Driver
	 */
	public static function factory($driver)
	{
		switch($driver)
		{
			case 'mysql':
				return new Drivers\MySQL;

			case 'postgres':
				return new Drivers\Postgres;

			case 'sqlite':
				return new Drivers\SQLite;

			case 'sqlserver':
				return new Drivers\SQLServer;

			default:
				throw new \Exception("Database driver {$driver} is not supported.");			
		}
	}

	/**
	 * List all of the fields of a table in the database.
	 *
	 * @return array
	 */
	public static function fields($table)
	{
		// The list of fields for a table will be internally cached.
		if( ! isset(static::$fields[$table]))
		{
			static::$fields[$table] = static::instance()->fields($table);
		}

		return static::$fields[$table];
	}
}