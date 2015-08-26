<?php //login.php
$db_hostname = 'localhost';
$db_database = 'test';
$db_username = 'root';
$db_password = '';

// Connect to server.
$db_server = mysqli_connect($db_hostname, $db_username, $db_password, $db_database)
    or die("Unable to connect to MySQL: " . mysqli_error());
	

?>