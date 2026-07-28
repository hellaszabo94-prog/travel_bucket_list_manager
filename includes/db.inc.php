<?php

// connect with the Mysql database

function dbConnect():mysqli {
	try {
		$conn = new mysqli(DB["hostadresse"],DB["username"],DB["passwort"],DB["DBName"]);
	}
	catch(Exception $e) {
		if(TESTOPERATION) {
			test($e);
			die("Connection error");
		}
		else {
			header("Location: errors/dbconnect.html");
		}
	}
	
	return $conn;
}


?>