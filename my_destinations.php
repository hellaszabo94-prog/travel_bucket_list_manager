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

$listMsg="";

$updatedDestinationID = null;

/*STATUS UPDATE*/

if (isset($_POST["changeStatus"]) && isset($_POST["typeOfStatus"]) && isset($_POST["destinationID"])) {

    $statusID =  (int) $_POST["typeOfStatus"];
    $destinationID =  (int) $_POST["destinationID"];

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
    
    $destinationID = (int) $_POST["destinationID"];

    // gets the image path before deleting the destination
    $sql = "
        SELECT
            i.ImagePath
        FROM tbl_image AS i

        INNER JOIN tbl_destination AS d
            ON i.FIDDestination = d.IDDestination

        WHERE
            d.IDDestination = " . $destinationID . "
            AND d.FIDUser = " . $userID . "
    ";

    $imageResult = dbQuery($conn, $sql);
    $destinationImage = dbFetch($imageResult);

    //deletes only a destination by logged-in user
    $sql = "
        DELETE FROM tbl_destination
        WHERE
            IDDestination = " . $destinationID . "
            AND FIDUser = " . $userID . "
    ";

    $deleteOk = dbQuery($conn, $sql);

    if ($deleteOk && $conn->affected_rows === 1){

        // deletes the physical image file if one exists.
        if ($destinationImage !== null) {

            $fileSystemPath =__DIR__ . "/" . $destinationImage->ImagePath;

            if (file_exists($fileSystemPath)) {

                unlink($fileSystemPath);
            }
        }

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

/*DESTINATION SEARCH*/

$searchCondition = "";
$search = "";
$isSearching = false;

if (
    isset($_POST["searchDestination"]) &&
    isset($_POST["search"]) &&
    strlen(trim($_POST["search"])) > 0
) {

    $isSearching = true;
    
    $search = trim($_POST["search"]);

    $searchSql = $conn->real_escape_string($search);

    // add the search filters to the destination query
    $searchCondition = "
        AND (
            d.DestinationName LIKE '%" . $searchSql . "%'
            OR c.CityName LIKE '%" . $searchSql . "%'
            OR co.CountryName LIKE '%" . $searchSql . "%'
        )
    ";
}

/*DESTINATION SEARCH*/

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
            s.StatusName,
            i.ImagePath

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

        -- connects each destination to uploaded image
        LEFT JOIN tbl_image AS i
             ON d.IDDestination = i.FIDDestination    

        -- shows only the destinations of the logged-in user    
        WHERE d.FIDUser = " . $userID . "

        " . $searchCondition . "

        -- displays the most recently added destinations first
        ORDER BY d.CreatedAt DESC

    ";

// executes the database query
$destinationResult = dbQuery($conn, $sql);

/*USER DESTINATIONS*/

// no result after search

if ($destinationResult->num_rows === 0) {

    if ($isSearching) {

        $listMsg='  <div class="col-span-full mb-8 w-full rounded-xl border border-travel-200 bg-white p-6 text-center shadow-sm">
                        <h2 class="info font-display text-xl font-bold text-travel-900">No destinations found.</h2>
                        <p class="mt-2 text-travel-700">Try another destination, city or country.</p>
                    </div>
                    ';
        
    } else {

        $listMsg='  <div class="col-span-full mb-8 w-full  rounded-xl border border-travel-200 bg-travel-50 p-6 text-center">
                        <h2 class="info font-display text-xl font-bold text-travel-900">No destinations yet</h2>
                        <p class="mt-2 text-travel-700">Start building your travel bucket list by adding your first destination.</p>
                    </div>
                    ';

    }

}




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
    <body class="min-h-screen bg-gradient-to-br from-travel-50 via-white to-travel-100 text-travel-950">
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
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold bg-gradient-to-r from-travel-900 to-travel-200 bg-clip-text text-transparent">My Destinations</h1>
            <p class="mt-2 font-medium text-travel-800">View and manage your saved travel destinations.</p>
        </div>    
        <form method="post" class="mb-8 rounded-xl border border-travel-100 bg-white p-6 shadow-sm">
            <label for="search" class="block text-xl font-bold font-display text-travel-800">Search destinations:</label>
            <div class="mt-2 flex flex-col gap-3 md:flex-row">
                <input type="text" id="search" name="search"  class="w-full flex-1 rounded-lg border border-travel-200 px-2 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" value="<?= isset($_POST["search"])? htmlspecialchars($_POST["search"],ENT_QUOTES,"UTF-8"): ""?>">
                <button type="submit" class=" font-display bg-gradient-to-b from-travel-800 to-travel-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm hover:ring-4 hover:ring-travel-500/50 transition-all duration-200 cursor-pointer" name="searchDestination">Search</button>
            </div>
        </form>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">    
        <?php
        
        echo ($listMsg);
        echo ($deleteMsg);

        // goes through all destinations returned by the query
        while ($destination = dbFetch($destinationResult)) {

            echo ('<article class="overflow-hidden px-3 py-3 rounded-xl border border-travel-100 bg-white shadow-sm flex flex-col h-full hover:shadow-lg hover:-translate-y-1 transition-all duration-200">');
            // displays the destination name
            echo (  '<h2 class="font-display text-2xl font-bold text-travel-800 py-3">' .
                    htmlspecialchars(
                        $destination->DestinationName,
                        ENT_QUOTES,
                        "UTF-8"
                    ) .
                    "</h2>"
                );
            //form for delete

            if ($destination->ImagePath !== null) {

                echo('<img src="' . htmlspecialchars($destination->ImagePath, ENT_QUOTES, "UTF-8") . '" class="destinationImage w-full h-66 object-cover border-3 border-travel-600 rounded-lg">');

            } else {

                echo('<div class="h-66 w-full bg-travel-100 flex items-center justify-center border-3 border-travel-600 rounded-lg">
                        <p class="font-display font-bold text-travel-500"> No photo yet </p>
                    </div>');

            }

            // displays the success message if status was updated
            if ($updatedDestinationID !== null && $destination->IDDestination === $updatedDestinationID) {
                echo ($msg);
            }

            echo ('<div class="p-5 flex flex-col flex-1">
                    <p class="mt-1 font-display font-bold text-travel-700">City:   ' .
                        htmlspecialchars(
                            $destination->CityName,
                            ENT_QUOTES,
                            "UTF-8"
                        ) .
                    '</p>');

            echo ('<p class="mt-1 font-display font-bold text-travel-700">Country: ' .
                htmlspecialchars(
                    $destination->CountryName,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
                    "</p>");

            echo ('<p class="mt-1 font-display font-bold text-travel-700">Status:  ' .
                htmlspecialchars(
                    $destination->StatusName,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
                    "</p>
                ");
            
            echo ('<p class="mt-4 text-travel-800 leading-relaxed line-clamp-3 whitespace-normal break-words">' .
                htmlspecialchars(
                   $destination->Description,
                    ENT_QUOTES,
                    "UTF-8"
                ) .
                    "</p>
                </div>");    

            echo ('
                    <form method="post">
                        <fieldset class="rounded-lg bg-travel-50 p-3">
                            <h3 class="font-display font-bold text-travel-900">Change status</h3>
                            <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '">
                            <div class="mt-5 flex flex-col gap-2">
                ');

            // creates a radio button for every status 
            foreach ($statuses as $status){

                $radioID ="status-" .  $destination->IDDestination . "-" . $status->IDStatus;

                $checked = ($destination->FIDStatus === $status->IDStatus) ? " checked" : "";
    
                    echo ('
                                <label  class="flex items-center gap-2 cursor-pointer rounded-md px-2 py-1 hover:bg-travel-100 transition" for="' . $radioID . '"> 
                                    <input type="radio" id="'. $radioID .'" name="typeOfStatus" class="accent-travel-600" value="' . $status->IDStatus . '"' . $checked . 'required > 
                                    <span class="text-travel-800">' . htmlspecialchars($status->StatusName,ENT_QUOTES,"UTF-8") . ' </span> 
                                </label>
                        ');
            }

            echo ('         </div>
                        </fieldset>
                            <button type="submit" class="mt-4 bg-travel-800 text-white font-bold px-4 py-2 rounded-lg shadow-sm hover:text-travel-500 duration-200 cursor-pointer" name="changeStatus">Change status</button>
                        </form>
                ');

            echo ('<div class="mt-auto pt-6 flex flex-wrap gap-3">');

            if ($destination->ImagePath === null) {
                // no image shows upload button   
                echo ('
                    <form method="post" action="upload_image.php" >
                        <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '" >
                            <button type="submit" class="bg-travel-100 text-travel-900 font-bold px-4 py-2 rounded-lg border border-travel-200 hover:bg-travel-200 transition cursor-pointer" name="openImageUpload">
                                Upload photo
                            </button>
                    </form>
                ');
            } else{
                // image already exists shows edit image button what send me to the upload images site
                echo ('
                    <form method="post" action="upload_image.php" >
                        <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '" >
                            <button type="submit" class="bg-travel-100 text-travel-900 font-bold px-4 py-2 rounded-lg border border-travel-200 hover:bg-travel-200 transition cursor-pointer" name="changeImage">
                                Edit image
                            </button>
                    </form>
                ');

            }
            echo ('
                    <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this destination?\');">
                        <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '">
                            <button type="submit" name="deleteDestination" class="bg-red-500 text-white font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-red-600 transition cursor-pointer">
                                Delete destination
                            </button>
                    </form>
                
            ');

            echo ('</div>');
                  
            echo ('</article>');              
        }
    ?>
    </div>
    </main>
    </body>
</html>