<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/db.inc.php");
require ("includes/auth.inc.php");

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
    <h2>Add a new destination</h2>
    <nav>
		<ul>
	    	<li><a href="">Suchen</a></li>
			<li><a href="add_destination.php">Add a new destination</a></li>
			<li><a href="">Reiseziel Status ändern</a></li>
            <li><a href="">Fotos hochladen</a></li>
            <li><a href="logout.php">Log out</a></li>
		</ul>
    </nav>
    <form method="post">
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="">Choose status</option>
            <option value="1">Want to visit</option>
            <option value="2">Planned</option>
            <option value="3">Visited</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <button type="submit" name="saveDestination">
            Save destination
        </button>

</form>

    </body>
</html>