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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/tailwind.css">
        <link rel="stylesheet" href="css/stylesheet.css">
        <script src="js/navigation.js"></script>
    </head>
    <body class="bg-travel-50 text-travel-950 min-h-screen">
    <header class="bg-white border-b border-travel-100 shadow-sm">
        <nav id="mobile" class="max-w-7xl mx-auto px-4 py-4 flex flex-wrap items-center gap-4 md:gap-8">
            <img src="logo/logo.png" alt="Travel Bucket List Manager logo" class="w-36 md:w-auto md:h-12 self-start md:self-auto">
            <button type="button" id="navRwd" class="ml-auto flex h-10 w-10 items-center justify-center rounded-lg bg-travel-100 hover:bg-travel-200 transition md:hidden cursor-pointer">
                <img id="menuIcon" class="h-6 w-6" src="./svg/menu.svg">
                <img id="closeIcon" class="hidden h-6 w-6" src="./svg/close.svg">
            </button>
            <ul class="hidden w-full flex-col gap-3 md:flex md:w-auto md:flex-row md:gap-6">
                <li><a href="dashboard.php" class="font-display font-bold text-travel-800 hover:text-travel-600 hover:underline underline-offset-8 transition">Home</a></li>
                <li><a href="add_destination.php" class="font-display font-bold text-travel-800 hover:text-travel-600 hover:underline underline-offset-8 transition">Add a new destination</a></li>
                <li><a href="my_destinations.php" class="font-display font-bold text-travel-800 hover:text-travel-600 hover:underline underline-offset-8 transition">My Destinations</a></li>
                <li class="md:hidden"><a href="logout.php" class="inline-block bg-travel-800 text-white font-display font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-travel-600 transition">Log out</a></li>
            </ul>
            <a href="logout.php" class="hidden md:inline-block font-display md:ml-auto bg-travel-800 text-white font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-travel-600 transition">Log out</a>
        </nav>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-8"> 
        <section class="bg-white rounded-xl border border-travel-100 shadow-sm p-6">
            <h1 class="font-display text-3xl font-bold bg-gradient-to-r from-travel-900 to-travel-500 bg-clip-text text-transparent ">Add a new destination</h1>
            <form method="post">
                <div class="mt-6">
                    <label for="country" class="block font-display font-bold text-travel-800">Country:</label>
                    <input type="text" id="country" name="country" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2" required>
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
                </div> 
                <div class="mt-6">  
                    <label for="city" class="block font-display font-bold text-travel-800">City:</label>
                    <input type="text" id="city" name="city" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2" required>
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
                    </div>
                    <div class="mt-6">
                        <label for="destination" class="block font-display font-bold text-travel-800">Destination:</label>
                        <input type="text" id="destination" name="destination" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2" required>
                        <div class="mt-6">
                            <p class=" font-bold font-display text-travel-800">Status:</p>

                            <div class="mt-3 flex flex-col gap-2">
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
                                    
                                        echo ('<label class="flex items-center gap-2 cursor-pointer">

                                                <input type="radio" name="typeOfStatus" class="accent-travel-600" value="' . $statusResult->IDStatus . '" required>

                                                <label  class="text-travel-800">' . htmlspecialchars($statusResult->StatusName, ENT_QUOTES,"UTF-8") . '</label>');
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
                            </div> 
                        </div> 
                    </div>
                    <div class="mt-6">        
                        <label for="description" class="block font-display font-bold text-travel-800">Description:</label>
                        <textarea id="description" name="description" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2"></textarea>
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
                    </div>     
                    <button type="submit" class="mt-6 font-display bg-gradient-to-b from-travel-800 to-travel-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm hover:ring-4 hover:ring-travel-500/50 transition-all duration-200 cursor-pointer" name="saveDestination">Save destination</button>
                </form>
            </section>        
        </main>
        <footer class="mt-10 border-t border-travel-200">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <p class="text-sm text-travel-700">© 2026 Hella Haraszti-Szabo. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>