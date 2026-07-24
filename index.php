<?php
// add the two includes file
require("includes/config.inc.php");
require("includes/common.inc.php");

//call the test function
test($_POST);

//check data form $_Post and send on the next page
if(isset($_POST["logButton"])){

    header("Location: log.php");
    exit;
} elseif(isset($_POST["regButton"])){

    header("Location: reg.php");
    exit;
}
?>
<!doctype html>
<html lang="de">
    <head>
        <title>Start</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Willkommen! Melden Sie sich an oder registrieren Sie sich.</h2>
    <form method="post">
        <input type="submit" value="Login" name="logButton">
        <input type="submit" value="Registrierung" name="regButton">
    </form>
    </body>
</html>