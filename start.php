<?php

Autoloader::map(array(

	'crud\DBUtil' => __DIR__.DS.'models'.DS.'dbutil.php',

	'crud\DBUtil\Drivers\Driver'    => __DIR__.DS.'models'.DS.'dbutil'.DS.'drivers'.DS.'driver.php',
	'crud\DBUtil\Drivers\MySQL'     => __DIR__.DS.'models'.DS.'dbutil'.DS.'drivers'.DS.'mysql.php',
	'crud\DBUtil\Drivers\Postgres'  => __DIR__.DS.'models'.DS.'dbutil'.DS.'drivers'.DS.'postgres.php',
	'crud\DBUtil\Drivers\SQLite'    => __DIR__.DS.'models'.DS.'dbutil'.DS.'drivers'.DS.'sqlite.php',
	'crud\DBUtil\Drivers\SQLServer' => __DIR__.DS.'models'.DS.'dbutil'.DS.'drivers'.DS.'sqlserver.php',
));