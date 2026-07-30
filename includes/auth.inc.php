<?php
test($_POST);

// start the session and load the stored session data

session_start();

// check if the user is actually logged in

if(!(isset($_SESSION["successLogin"]) && $_SESSION["successLogin"]===true)){
    header("Location: log.php");
}

?>