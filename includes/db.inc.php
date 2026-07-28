<?php

// connect with the Mysql database

function dbConnect():mysqli {
	try {
		$conn_int = new mysqli(DB["hostadresse"],DB["username"],DB["passwort"],DB["DBName"]);
	}
	catch(Exception $e) {
		if(TESTOPERATION) {
			test($e);
			die("Connection error");
		}
		else {
			header("Location: errors/dbconnect.html");
            exit;
		}
	}
	
	return $conn_int;
}

// query from database

function dbQuery(mysqli $conn_int, string $sql_int):mysqli_result|bool {
	try {
		$answer_int = $conn_int->query($sql_int);
	}
	catch(Exception $e) {
		if(TESTOPERATION) {
			test($e);
			die("Error in the Query");
		}
		else {
			header("Location: errors/dbquery.html");
            exit;
		}
	}
	
	return $answer_int;
}

// fetching the query result

function dbFetch(mysqli_result $answer_int):object|null {

	return $answer_int->fetch_object(); 
}
?>