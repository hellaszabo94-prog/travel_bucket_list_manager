<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/auth.inc.php");
require ("includes/db.inc.php");

// gets the ID of the currently logged-in user
$userID = (int) $_SESSION["userID"];

$conn = dbConnect();

$msg = "";
$deleteMsg="";

$updatedDestinationID = null;

/*STATUS UPDATE*/

if (isset($_POST["changeStatus"]) && isset($_POST["typeOfStatus"]) && isset($_POST["destinationID"])) {

    $statusID = $_POST["typeOfStatus"];
    $destinationID = $_POST["destinationID"];

    $sql = "
            UPDATE tbl_destination

            SET FIDStatus = " . $statusID . "
            
            WHERE
                IDDestination = " . $destinationID . "
                AND FIDUser = " . $userID . "
        ";

    $statusOk = dbQuery($conn, $sql);

    if ($statusOk) {
        $msg = '<p class="success">Status updated successfully.</p>';
        
        //remembers which destination was updated.
        $updatedDestinationID = $destinationID;
    }  

  
}  

/*STATUS UPDATE*/     

/*DESTINATION DELETE*/

if (isset($_POST["deleteDestination"]) && isset($_POST["destinationID"])) {
    
    $destinationID = $_POST["destinationID"];

     //deletes only a destination by logged-in user
    $sql = "
        DELETE FROM tbl_destination
        WHERE
            IDDestination = " . $destinationID . "
            AND FIDUser = " . $userID . "
    ";

    $deleteOk = dbQuery($conn, $sql);

    if ($deleteOk && $conn->affected_rows === 1){

        $deleteMsg = '<p class="success">Destination deleted successfully.</p>';

    } else{

        $deleteMsg = '<p class="error">The destination could not be deleted.</p>';

    }
}

/*DESTINATION DELETE*/

/*AVAILABLE STATUSES*/

 // loads all available statuses from the database to update the status
$sql="

    SELECT
        IDStatus,
        StatusName

    FROM tbl_status

    ORDER BY IDStatus ASC   
    ";
    $statusResult = dbQuery($conn,$sql);

    $statuses = [];

    // Stores all available statuses in an array.
    while ($status = dbFetch($statusResult)) {
        $statuses[] = $status;
    }

/*AVAILABLE STATUSES*/

/*USER DESTINATIONS*/

// selects all destinations
$sql = "
        SELECT
            d.IDDestination,
            d.DestinationName,
            d.Description,
            d.CreatedAt,
            c.CityName,
            co.CountryName,
            d.FIDStatus,
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

/*USER DESTINATIONS*/



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
        
        echo ($deleteMsg);

        // Displays a message if the user has no saved destinations.
        if ($destinationResult->num_rows === 0) {

            echo ($msg='<p>No destinations have been saved yet.</p>');

        }

        // goes through all destinations returned by the query
        while ($destination = dbFetch($destinationResult)) {

            echo ('<article class="destination">');
            // displays the destination name
            echo (  "<h3>" .
                    htmlspecialchars(
                        $destination->DestinationName,
                        ENT_QUOTES,
                        "UTF-8"
                    ) .
                    "</h3>"
                );
            //form for delete


            // displays the success message if status was updated
            if ($updatedDestinationID !== null && $destination->IDDestination === $updatedDestinationID) {
                echo ($msg);
            }

            echo ("<p>City: " .
                htmlspecialchars(
                    $destination->CityName,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
            "</p>");

            echo ("<p>Country: " .
                htmlspecialchars(
                    $destination->CountryName,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
            "</p>");

            echo ("<p>Current Status: " .
                htmlspecialchars(
                    $destination->StatusName,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
            "</p>");

            echo ('<form method="post">
                        <fieldset>
                            <legend>Change status</legend>
                            <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '"> 
                ');

            // creates a radio button for every status 
            foreach ($statuses as $status){

                $radioID ="status-" .  $destination->IDDestination . "-" . $status->IDStatus;

                $checked = ($destination->FIDStatus === $status->IDStatus) ? " checked" : "";
    
                    echo ('<input type="radio" id="'. $radioID .'" name="typeOfStatus" value="' . $status->IDStatus . '"' . $checked . 'required >
                                <label for="' . $radioID . '">' . htmlspecialchars($status->StatusName,ENT_QUOTES,"UTF-8") . '</label>
                        ');
            }

            echo ('     </fieldset>
                            <button type="submit" name="changeStatus">Change status</button>
                    </form>
                ');
            echo ('
                <form
                    method="post"
                    onsubmit="return confirm(\'Are you sure you want to delete this destination?\');"
                >
                    <input
                        type="hidden"
                        name="destinationID"
                        value="' . $destination->IDDestination . '"
                    >

                    <button
                        type="submit"
                        name="deleteDestination"
                        
                    >
                        Delete destination
                    </button>
                </form>
            ');  
            echo ('
                <form
                    method="post"
                    action="upload_image.php"
                >
                    <input
                        type="hidden"
                        name="destinationID"
                        value="' . $destination->IDDestination . '"
                    >

                    <button
                        type="submit"
                        name="openImageUpload"
                        
                    >
                        Upload photo
                    </button>
                </form>
            ');
            echo ('</article>');              
        }
    ?>
    </main>
    </body>
</html>