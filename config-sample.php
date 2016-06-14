<?php
#Copy this file and rename it config.php
ob_start();
session_start();

#Set these values to configure your database connection
define('DBHOST', 'YourDatabaseLocation');
define('DBUSER', 'YourDatabaseUser');
define('DBPASSWORD', 'YourDatabaseUserPassword');
define('DBNAME', 'YourDatabaseName');

#Set this value to be how you would reach the application through your browser
define('DIR', 'http://localhost/example/');

try {
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
} catch(PDOException $e) {
	
	die('<p class="bg-danger">'.$e->getMessage().'</p>');
}

include('classes/user.php');
$user = new User($db);
?>