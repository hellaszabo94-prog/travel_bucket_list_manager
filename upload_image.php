<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/db.inc.php");
require ("includes/auth.inc.php");

$conn = dbConnect();

$msg="";

$userID = (int) $_SESSION["userID"];

$sql = "
    SELECT
        IDDestination,
        DestinationName
    FROM tbl_destination
    WHERE FIDUser = " . $userID . "
    ORDER BY DestinationName ASC
";

$destinationResult = dbQuery($conn, $sql);

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