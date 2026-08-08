<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/db.inc.php");
require ("includes/auth.inc.php");

$conn = dbConnect();

test($_FILES);

$msg="";

$picturesWhiteList = ["image/jpeg","image/gif","image/png","image/webp","image/avif","image/svg+xml"];

$userID = (int) $_SESSION["userID"];
$destinationID =  ($_POST["destinationID"]);

$sql = "
    SELECT
        IDDestination,
        DestinationName
    FROM tbl_destination
    WHERE
        IDDestination = " . $destinationID . "
        AND FIDUser = " . $userID . "
";

$destinationResult = dbQuery($conn, $sql);
$destination = dbFetch($destinationResult);

if(count($_FILES)>0) {

    $picture = $_FILES["destinationImage"];

    if($picture["error"]==0) {

        if(in_array($picture["type"],$picturesWhiteList)) {

            $ok = move_uploaded_file($picture["tmp_name"],"./uploads" . $picture["name"]);

			if($ok) {
				$msg = '<p class="success"></p>';
			}
			else {
				$msg = '<p class="error"></p>';
			}

        }
    }    
    
	



}

?>

<!doctype html>
<html lang="en">
	<head>
		<title>Travel Bucket List Manager</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/stylesheet.css">
	</head>
	<body>
		<form method="post" enctype="multipart/form-data">
			<label for="destinationImage">Choose an image:
                <input type="file" id="destinationImage" name="destinationImage" required>
			</label>
			<input type="submit" value="hochladen">
		</form>
	</body>
</html>