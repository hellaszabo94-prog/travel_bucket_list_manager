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
            exit;
		}
	}
	
	return $conn;
}

// query from database

function dbQuery(mysqli $conn, string $sql):mysqli_result|bool {
	try {
		$answer = $conn->query($sql);
	}
	catch(Exception $e) {
		if(TESTOPERATION) {
			test($e);
			die("Error in the Query");
		}
		else {
			header("Location: errors/dbquery.html");
		}
	}
	
	return $answer;
}

// fetching the query result

function dbFetch(mysqli_result $answer):object|null {

	return $answer->fetch_object(); 

// prepares text data for SQL statements

function checkIsItEmpty(string $incomeing):string {

	if(strlen($incoming)>0) {
		$out = "'" . $incomeing . "'";
	}
	else {
		$out = "NULL";
	}
	return $out;
}

?>