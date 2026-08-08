<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/db.inc.php");
require ("includes/auth.inc.php");

$conn = dbConnect();

test($_FILES);

$msg="";

// allowed image MIME types
$picturesWhiteList = [
						"image/jpeg" => "jpg",
						"image/gif" => "gif",
						"image/png" => "png",
						"image/webp" => "webp",
						"image/avif" => "avif"
					];

// gets the logged-in user's ID
$userID = (int) $_SESSION["userID"];

// gets the selected destination ID
if (!isset($_POST["destinationID"])) {

    $msg = '<p class="error">Destination ID is missing.</p>';

} else {
	
    $destinationID = (int) $_POST["destinationID"];

	// checks the destination belongs to the logged-in user

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

	if ($destination === null) {

		$msg = '<p class="error">Destination not found.</p>';

	} elseif(count($_FILES)>0) {

		$picture = $_FILES["destinationImage"];

		

		if($picture["error"] === UPLOAD_ERR_OK) {

			// determines the real MIME type of the uploaded file
			$fileInfo = new finfo(FILEINFO_MIME_TYPE);
			$mimeType = $fileInfo->file($picture["tmp_name"]);

			// checks the MIME type is allowed
			if(isset($picturesWhiteList[$mimeType])) {

				// defines the physical upload directory
				$directory = __DIR__ . "/uploads/destinations/";

				// creates the directory if it does not exist
				if (!is_dir($directory)) {

					mkdir($directory, 0755, true);

				}


				// generates a unique filename
				$fileName = bin2hex(random_bytes(16)) . "." . $picturesWhiteList[$mimeType];

				// physical path 
				$fileSystemPath =$directory . $fileName;

				// relative path store in the database
				$imagePath = "./uploads/destinations/" . $fileName;

				// moves the uploaded image to the destination folder
				$ok = move_uploaded_file( $picture["tmp_name"], $fileSystemPath);

				if($ok) {

					$imagePathSql =	$conn->real_escape_string($imagePath);

					// saves the image path and destination ID in the database
					$sql = "
						INSERT INTO tbl_image
							(
								FIDDestination,
								ImagePath
							)
						VALUES (
							" . $destinationID . ",
							'" . $imagePathSql . "'
						)
					";

					$imageOk = dbQuery($conn, $sql);

					if ($imageOk) {

						$msg = '<p class="success">Image uploaded successfully.</p>';

					}
					else {

						unlink($fileSystemPath);

						$msg = '<p class="error">The image could not be saved.</p>';
						
					}
				}
				else {
					$msg = '<p class="error">An error occurred during the image upload.</p>';
				}

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
		<h1>Upload destination image</h1>
		<?php echo($msg);?>
		<form method="post" enctype="multipart/form-data">
			<label for="destinationImage">Choose an image:
                <input type="file" id="destinationImage" name="destinationImage" required>
			</label>
			<input type="submit" value="hochladen">
		</form>
	</body>
</html>