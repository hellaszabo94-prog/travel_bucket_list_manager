<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/db.inc.php");
require ("includes/auth.inc.php");

$conn = dbConnect();

$msg="";

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
			<li><a href="my_destinations.php">My Destinations</a></li>
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
           <?php
                // loads all available statuses from the database

                $sql="
                    SELECT
                        IDStatus,
                        StatusName
                    FROM tbl_status
                    ORDER BY IDStatus ASC   
                ";
                $status = dbQuery($conn,$sql);

                // creates a radio button for every status 
                while($statusResult = dbFetch($status)){
                   
                    echo ('<input type="radio" name="typeOfStatus" value="' . $statusResult->IDStatus . '" required>

                            <label>' . htmlspecialchars($statusResult->StatusName, ENT_QUOTES,"UTF-8") . '</label>');
                }

                $statusID = null;

                // shows an error if no status was selected
                if (!isset($_POST["typeOfStatus"])) {

                        $msg = '<p class="error">Please select a status.</p>';

                } else {
                        // converts the selected status ID into an integer
                        $statusID = (int) $_POST["typeOfStatus"];
                }
                
            ?>
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>
              <?php
                // checks the destination field exists and is not empty

                if(isset($_POST["destination"]) && trim($_POST["destination"]) !== "" && $statusID !== null){
                    // prepares the optional description for the SQL query
                    $destinationfield =$conn->real_escape_string(trim($_POST["destination"]));

                    $descriptionfield = $conn->real_escape_string($_POST["description"]);
                    // gets the currently logged-in user's ID from the session
                    $userID = (int) $_SESSION["userID"];
                    // checks the destination is already saved by the current user
                    $sql = "
                    SELECT IDDestination
                    FROM tbl_destination
                    WHERE(
                        DestinationName ='" . $destinationfield . "'
                        AND FIDCity = " . $cityID . "
                        AND FIDUser = " . $userID ."
                    )
                    ";

                    $destinationResult = dbQuery($conn,$sql);

                     // creates the destination if it is not already exist
                    if($destinationResult->num_rows==0){

                        // saves the destination
                        $sql="
                                INSERT INTO tbl_destination
                                    (DestinationName, Description, FIDCity, FIDStatus, FIDUser)
                                VALUES (
                                '" . $destinationfield . "',
                                '" . $descriptionfield . "',
                                " . $cityID . ",
                                " . $statusID . ",
                                " . $userID . "
                                )
                        ";

                        $destinationOk = dbQuery($conn,$sql);

                        if($destinationOk){
                            // saves the ID of the newly created destination
                            $destinationID = $conn->insert_id;

                            echo($msg='<p class="success"> Destination saved.</p>');
                        }
                    
                    }
                    else{
                        echo($msg='<p class="error"> Destination already exists in the system.</p>');
                    }
                }
             ?>  
        <button type="submit" name="saveDestination">Save destination</button>

</form>

    </body>
</html>