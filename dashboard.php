<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/auth.inc.php");

test($_SESSION);


?>

<!doctype html>
<html lang="en">
    <head>
        <title>Travel Bucket List Manager</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Welcome to your personal Travel Bucket List Manager, <?= htmlspecialchars($_SESSION["Firstname"], ENT_QUOTES, "UTF-8") ?>!</h2>
    <nav>
		<ul>
	    	<li><a href="dashboard.php">Home</a></li>
			<li><a href="add_destination.php">Add a new destination</a></li>
			<li><a href="my_destinations.php">My Destinations</a></li>
            <li><a href="logout.php">Log out</a></li>
		</ul>
    </nav>
    </body>
</html>