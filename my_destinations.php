<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/auth.inc.php");
require ("includes/db.inc.php");

// gets the ID of the currently logged-in user
$userID = (int) $_SESSION["userID"];

$conn = dbConnect();

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
    <h2>My Destinations</h2>
    <nav>
		<ul>
	    	<li><a href="">Suchen</a></li>
			<li><a href="add_destination.php">Add a new destination</a></li>
			<li><a href="my_destinations.php">My Destinations</a></li>
            <li><a href="">Fotos hochladen</a></li>
            <li><a href="logout.php">Log out</a></li>
		</ul>
    </nav>
    <main>
        <?php
        // selects all destinations
        $sql = "
                SELECT
                    d.IDDestination,
                    d.DestinationName,
                    d.Description,
                    d.CreatedAt,
                    c.CityName,
                    co.CountryName,
                    s.StatusName

                FROM tbl_destination AS d

                -- connects each destination to its city
                INNER JOIN tbl_city AS c
                      ON d.FIDCity = c.IDCity

                -- connects each city to its country
                INNER JOIN tbl_country AS co
                    ON c.FIDCountry = co.IDCountry

                -- connects each destination to its current status
                INNER JOIN tbl_status AS s
                    ON d.FIDStatus = s.IDStatus

                -- shows only the destinations of the logged-in user    
                WHERE d.FIDUser = " . $userID . "

                -- displays the most recently added destinations first
                ORDER BY d.CreatedAt DESC

            ";

        // executes the database query
        $destinationResult = dbQuery($conn, $sql);

        // goes through all destinations returned by the query
        while ($destination = dbFetch($destinationResult)) {

    echo "<h3>" .
        htmlspecialchars(
            $destination->DestinationName,
            ENT_QUOTES,
            "UTF-8"
        ) .
    "</h3>";

    echo "<p>City: " .
        htmlspecialchars(
            $destination->CityName,
            ENT_QUOTES,
            "UTF-8"
        ) .
    "</p>";

    echo "<p>Country: " .
        htmlspecialchars(
            $destination->CountryName,
            ENT_QUOTES,
            "UTF-8"
        ) .
    "</p>";

    echo "<p>Status: " .
        htmlspecialchars(
            $destination->StatusName,
            ENT_QUOTES,
            "UTF-8"
        ) .
    "</p>";
}
    ?>
    </main>
    </body>
</html>