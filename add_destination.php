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
        <?php

            // check the country field exists and is not empty.
            if(isset($_POST["country"]) && trim($_POST["country"]) !== ""){

                    // remove unnecessary spaces and escapes special characters
                    $countryfield = $conn->real_escape_string(trim($_POST["country"]));

                    // check the country already exists in the database
                    $sql = "
                    SELECT IDCountry
                    FROM tbl_country
                    WHERE(
                        CountryName='" . $countryfield . "'
                    )
                    ";

                    $countryResult = dbQuery($conn,$sql);

                    // if the country does not exist, create a new
                    if($countryResult->num_rows===0){

                        $sql="
                                INSERT INTO tbl_country
                                    (CountryName)
                                VALUES (
                                '" . $countryfield . "'
                                )
                        ";
                        
                        $countryOk = dbQuery($conn,$sql);

                        if($countryOk){
                            // save the ID of the newly created country
                            $countryID = $conn->insert_id;

                            echo($msg='<p class="success">Country saved.</p>');

                        }
                    
                    }
                    else{
                        // if the country already exists, get ID from the database.
                        $country = dbFetch($countryResult);

                        $countryID = $country->IDCountry;
                    }
            }
        ?>   
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>
         <?php
                if(isset($_POST["city"]) && trim($_POST["city"]) !== ""){

                    $cityfield = $conn->real_escape_string(trim($_POST["city"]));

                    // check the city already exists in the selected country

                    $sql = "
                    SELECT IDCity
                    FROM tbl_city
                    WHERE(
                        CityName ='" . $cityfield . "'
                        AND FIDCountry = " . $countryID . "

                    )
                    ";

                    $cityResult = dbQuery($conn,$sql);

                    // if the city does not exist, create a new

                    if($cityResult->num_rows===0){

                        $sql="
                                INSERT INTO tbl_city
                                    (CityName, FIDCountry )
                                VALUES (
                                '" . $cityfield . "',
                                " . $countryID . " 
                                )
                        ";

                        $cityOk = dbQuery($conn,$sql);

                        if($cityOk){
                            // saves the ID of the newly created city
                            $cityID = $conn->insert_id;

                            echo($msg='<p class="success"> City saved.</p>');
                        }
                    }
                    else{
                        // if the city already exists, get its ID from the database
                        
                        $city = dbFetch($cityResult);
                         
                        $cityID = $city->IDCity;
                        
                    }
                }
             ?>   

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