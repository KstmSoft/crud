<?php
	$host = "mysql";
	$db_name = "db";
	$username = "dbuser";
	$password = "dbpass";
	try{
	    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
	}catch(PDOException $exception){
	    die("Error de conexion: " . $exception->getMessage());
	}
?>