<?php
test($_POST);

session_start();

test($_SESSION["Emailadress"]);


if(!(isset($_SESSION["successLogin"]) && $_SESSION["successLogin"]===true)){
    header("Location: log.php");
}

?>