<?php

Autoloader::map(array(

	'scaffold\\Table' => __DIR__.DS.'libraries'.DS.'table.php',

	'scaffold\\Drivers\\Driver'    => __DIR__.DS.'libraries'.DS.'drivers'.DS.'driver.php',
	'scaffold\\Drivers\\MySQL'     => __DIR__.DS.'libraries'.DS.'drivers'.DS.'mysql.php',
	'scaffold\\Drivers\\Postgres'  => __DIR__.DS.'libraries'.DS.'drivers'.DS.'driver.php',
	'scaffold\\Drivers\\SQLite'    => __DIR__.DS.'libraries'.DS.'drivers'.DS.'sqlite.php',
	'scaffold\\Drivers\\SQLServer' => __DIR__.DS.'libraries'.DS.'drivers'.DS.'sqlserver.php',
));