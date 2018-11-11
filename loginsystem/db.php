<?php
session_start();

// Define database
define('host', 'localhost');
define('dbuser', 'n9405194');
define('dbpass', 'timtam01ALPHA');
define('dbname', 'n9405194');

// Connecting database
try {
	$connect = new PDO("mysql:host=".host."; dbname=".dbname, dbuser, dbpass);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo $e->getMessage();
}

?>
