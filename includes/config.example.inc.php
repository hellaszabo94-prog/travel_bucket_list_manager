<?php 
//create a constant to the TESTOPERATION turn in or turn of
define("TESTOPERATION",true); 

// stores database settings in an associative array constant.
define("DB",[
	"hostadresse" => "localhost",
	"username" => "database_username",
	"passwort" => "database_password",
	"DBName" => "database_name",
]);

// if TESTOPERATION is active, display all errors; otherwise, hide them.
if(TESTOPERATION) {
	error_reporting(E_ALL);
	ini_set("display_errors",1);
}
else {
	error_reporting(E_ALL);
	ini_set("display_errors",0);
}
?>