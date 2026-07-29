<?php
require ("includes/common.inc.php");
require ("includes/config.inc.php");

test($_POST);

session_start();

test($_SESSION["Emailadress"]);


if(!(isset($_SESSION["successLogin"]) && $_SESSION["successLogin"]===true)){
    header("Location: log.php");
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>Travel Bucket List Manager</title>
        <meta charset="utf_8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Welcome to your personal Travel Bucket List Manager, 
        <?= htmlspecialchars($_SESSION["Emailadress"], ENT_QUOTES, "UTF-8") ?>!</h2>
    <form method="post">
        <input type="submit" value="Log out" name="Logout">
    </form>
    <nav>
		<ul>
	    	<li><a href="">Suchen</a></li>
			<li><a href="">Neue Reiseziel speichern</a></li>
			<li><a href="">Reiseziel Status ändern</a></li>
            <li><a href="">Fotos hochladen</a></li>
            <li><a href="logout.php">Log out</a></li>
		</ul>
    </nav>
    </body>
</html>