<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/db.inc.php");
require ("includes/auth.inc.php");

$conn = dbConnect();

//test($_FILES);

$msg="";
$destination = null;
$existingResult = null;

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

	/*DESTINATION CHECK*/

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

	/*DESTINATION CHECK*/

	} else {

		/*EXISTING IMAGE CHECK*/

			// checks the destination already has an image.
			$sql = "
					SELECT
						IDImage,
						ImagePath
					FROM tbl_image
					WHERE FIDDestination = " . $destinationID . "
				";
			
			$existingImage = dbQuery($conn, $sql);	
			$existingResult = dbFetch($existingImage);
	}	

		/*EXISTING IMAGE CHECK*/	

		/* DELETE IMAGE */
		if (isset($_POST["deleteImage"]) && $existingResult !== null) {
	

        	$oldImagePath = $existingResult->ImagePath;

			$sql = "
					DELETE FROM tbl_image
					WHERE IDImage = " . $existingResult->IDImage . "
			";

			$deleteImageOk = dbQuery($conn, $sql);

			if ($deleteImageOk && $conn->affected_rows === 1) {

				$fileSystemPath = __DIR__ . "/" . $oldImagePath;

				    if (file_exists($fileSystemPath)) {

                		unlink($fileSystemPath);
            		}

				$msg ='<p class="success">Image deleted successfully.</p>';	

				$existingResult = null;

			}else {

            	$msg ='<p class="error">The image could not be deleted.</p>';
        	}
		}
		/* DELETE IMAGE */

		/*IMAGE UPLOAD*/

	if(count($_FILES)>0) {

		$picture = $_FILES["destinationImage"];

		$maxFileSize = 4 * 1024 * 1024;

		if($picture["error"] === UPLOAD_ERR_OK) {

			if ($picture["size"] > $maxFileSize) {

				$msg ='<p class="error">The image must not be larger than 4 MB.</p>';
			} else {

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
					$fileSystemPath = $directory . $fileName;

					// relative path store in the database
					$imagePath = "./uploads/destinations/" . $fileName;

					// moves the uploaded image to the destination folder
					$ok = move_uploaded_file( $picture["tmp_name"], $fileSystemPath);

					if($ok) {

						$imagePathSql =	$conn->real_escape_string($imagePath);

						/*NEW IMAGE*/
						if ($existingResult === null) {

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

								// creates the image object for the current page without running another SELECT query
								$existingResult = new stdClass();
								$existingResult->IDImage = $conn->insert_id;
								$existingResult->ImagePath = $imagePath;

							} else {

								unlink($fileSystemPath);

								$msg = '<p class="error">The image could not be saved.</p>';
								
							}
						
						/*REPLACE EXISTING IMAGE*/		
						} else {
							// saves the old image path before update
							$oldImagePath = $existingResult->ImagePath;

							$sql = "
									UPDATE tbl_image
									SET
										ImagePath = '" . $imagePathSql . "'
									WHERE
										FIDDestination = " . $destinationID . "
									";

							$updateImageOk = dbQuery($conn, $sql);

							if ($updateImageOk) {
								//creates the physical path of the old image
								$oldFileSystemPath = __DIR__ . "/" . $oldImagePath;
								
								// Deletes the old image file.
								if (file_exists($oldFileSystemPath)) {

										unlink($oldFileSystemPath);

								}

								$msg ='<p class="success"> Image changed successfully.</p>';

								$existingResult->ImagePath = $imagePath;

							}else {

								// removes the new image if upload not possible
								unlink($fileSystemPath);

								$msg ='<p class="error">The image could not be changed.</p>';
							}
						}	

					}else {

						$msg = '<p class="error">The image could not be uploaded.</p>';
					
					}
				}else {

					$msg = '<p class="error">This image type is not allowed.</p>';

				} 
			}	  
		}else {

			$msg = '<p class="error">Image upload failed.</p>';

		}
	}
		
}	
?>

<!doctype html>
<html lang="en">
	<head>
		<title>Travel Bucket List Manager</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/stylesheet.css">
	</head>
	<body>
		<h1>Travel Bucket List Manager</h1>
        <h2>Upload destination image</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="add_destination.php">Add a new destination</a></li>
                <li><a href="my_destinations.php">My Destinations</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </nav>
		<?php 
		
		echo($msg);

		if (isset($destination) && $destination !== null) {

		echo (  "<h3>" . htmlspecialchars($destination->DestinationName, ENT_QUOTES, "UTF-8" ) . "</h3>" );

		}		
		
		
		
		?>
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="destinationID" value=" <?= (int) $destination->IDDestination ?>">
			<label for="destinationImage">Choose an image:
                <input type="file" id="destinationImage" name="destinationImage" required>
			</label>
			<button type="submit" name="uploadImage" >
				<?php 
					if ($existingResult === null) {
        				echo ("Upload image");
    				} else {
        				echo ("Update image");
   					}
                 ?>
                </button>
		</form>
		<a href="my_destinations.php">Back to My Destinations</a>
		<?php 
			if (isset($existingResult) && $existingResult !== null){
				echo("<p>This destination already has an image:</p>");
				echo('<img src="' . htmlspecialchars($existingResult->ImagePath, ENT_QUOTES, "UTF-8") . '" class="destinationImage">');
				echo('
                    <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this image?\');">
                        <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '" >
                            <button type="submit" name="deleteImage">
                                Delete image
                            </button>
                    </form>
                ');
			}
			
			
			
		?>
		

	</body>
</html>