<?php

Autoloader::map(array(

	'Scaffold\\Table' => __DIR__.DS.'libraries'.DS.'table.php',

	'Scaffold\\Drivers\\Driver'    => __DIR__.DS.'libraries'.DS.'drivers'.DS.'driver.php',
	'Scaffold\\Drivers\\MySQL'     => __DIR__.DS.'libraries'.DS.'drivers'.DS.'mysql.php',
	'Scaffold\\Drivers\\Postgres'  => __DIR__.DS.'libraries'.DS.'drivers'.DS.'postgres.php',
	'Scaffold\\Drivers\\SQLite'    => __DIR__.DS.'libraries'.DS.'drivers'.DS.'sqlite.php',
	'Scaffold\\Drivers\\SQLServer' => __DIR__.DS.'libraries'.DS.'drivers'.DS.'sqlserver.php',
));