<?php namespace scaffold;

use DB;

class Table {

	/**
	 * The current active drivers.
	 *
	 * @var array
	 */
	public static $drivers = array();

	/**
	 * Get the instance of a driver.
	 *
	 * @param  string  $driver
	 * @return Driver
	 */
	public static function instance($driver = null)
	{
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
	 * List all of the tables in the database.
	 *
	 * @return array
	 */
	public static function all()
	{
		return static::instance()->all();
	}

	/**
	 * List all of the fields of a table in the database.
	 *
	 * @return array
	 */
	public static function fields($table)
	{
		return static::instance()->fields($table);
	}
}