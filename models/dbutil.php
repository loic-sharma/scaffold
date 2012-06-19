<?php namespace crud;

use Laravel\Database as DB;

class DBUtil {

	public static $drivers = array();

	public static function driver($driver = null)
	{
		if(is_null($driver)) $driver = DB::connection()->config['driver'];

		if( ! isset(static::$drivers[$driver]))
		{
			static::$drivers[$driver] = static::factory($driver);
		}

		return static::$drivers[$driver];
	}

	public static function factory($driver)
	{
		switch ($driver)
		{
			case 'mysql':
				return new DBUtil\Drivers\MySQL;

			case 'postgres':
				return new DBUtil\Drivers\Postgres;

			case 'sqlite':
				return new DBUtil\Drivers\SQLite;

			case 'sqlserver':
				return new DBUtil\Drivers\SQLServer;

			default:
				throw new \Exception("DBUtil driver {$driver} is not supported.");
		}
	}

	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}
}