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

    $msg = '<p cclass="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">Destination ID is missing.</p>';

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

		$msg = '<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">Destination not found.</p>';

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

				$msg ='<p class="mt-4 rounded-lg border border-travel-200 bg-travel-50 px-4 py-3 text-travel-800">Image deleted successfully.</p>';	

				$existingResult = null;

			}else {

            	$msg ='<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">The image could not be deleted.</p>';
        	}
		}
		/* DELETE IMAGE */

		/*IMAGE UPLOAD*/

	if(count($_FILES)>0) {

		$picture = $_FILES["destinationImage"];

		$maxFileSize = 4 * 1024 * 1024;

		if($picture["error"] === UPLOAD_ERR_OK) {

			if ($picture["size"] > $maxFileSize) {

				$msg ='<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">The image must not be larger than 4 MB.</p>';
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

								$msg = '<p class="mt-4 rounded-lg border border-travel-200 bg-travel-50 px-4 py-3 text-travel-800">Image uploaded successfully.</p>';

								// creates the image object for the current page without running another SELECT query
								$existingResult = new stdClass();
								$existingResult->IDImage = $conn->insert_id;
								$existingResult->ImagePath = $imagePath;

							} else {

								unlink($fileSystemPath);

								$msg = '<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">The image could not be saved.</p>';
								
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

								$msg ='<p class="mt-4 rounded-lg border border-travel-200 bg-travel-50 px-4 py-3 text-travel-800"> Image changed successfully.</p>';

								$existingResult->ImagePath = $imagePath;

							}else {

								// removes the new image if upload not possible
								unlink($fileSystemPath);

								$msg ='<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">The image could not be changed.</p>';
							}
						}	

					}else {

						$msg = '<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">The image could not be uploaded.</p>';
					
					}
				}else {

					$msg = '<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">This image type is not allowed.</p>';

				} 
			}	  
		}else {

			$msg = '<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">Image upload failed.</p>';

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
		<link rel="stylesheet" href="css/tailwind.css">
		<link rel="stylesheet" href="css/stylesheet.css">
		<script src="js/navigation.js"></script>
		<script src="js/upload-image.js" defer></script>
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
				<h1 class="font-display text-3xl font-bold bg-gradient-to-r from-travel-900 to-travel-200 bg-clip-text text-transparent">Manage destination image</h1> 
				<p class="mt-2 text-travel-700">Upload, replace or remove the image for your destination.</p>
			 
				<?php 
				
				echo($msg);

				if (isset($destination) && $destination !== null) {

				echo (  "<h3 class='my-6 font-display text-2xl font-bold text-travel-900'>" . htmlspecialchars($destination->DestinationName, ENT_QUOTES, "UTF-8" ) . "</h3>" );

				}		
				
				
				
				?>
			<div class="mt-6">
				<form method="post" enctype="multipart/form-data" class="flex flex-col gap-3 md:flex-row md:items-center">
					<input type="hidden" name="destinationID" value=" <?= (int) $destination->IDDestination ?>">
					<label for="destinationImage" class="inline-block cursor-pointer rounded-lg bg-travel-100 px-4 py-2 font-bold text-travel-800 hover:bg-travel-200 transition">Choose image</label>
					<input type="file" id="destinationImage" name="destinationImage" class="hidden" required>
					<p id="fileName" class="text-sm text-travel-700 md:flex-1">No file selected</p>
					<button type="submit" name="uploadImage" class="bg-travel-800 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm hover:ring-4 hover:ring-travel-500/40 transition-all duration-200 cursor-pointer" >
						<?php 
							if ($existingResult === null) {
								echo ("Upload image");
							} else {
								echo ("Update image");
							}
						?>
						</button>
				</form>
			</div>	
	
		<?php 
			if (isset($existingResult) && $existingResult !== null){
				echo('<p class="my-3 text-md font-medium text-travel-700">This destination already has an image:</p>');
				echo('<img src="' . htmlspecialchars($existingResult->ImagePath, ENT_QUOTES, "UTF-8") . '" alt="Current destination image" class="mt-6 w-full h-64 object-cover rounded-xl border-3 border-travel-600 md:max-w-180 md:h-100 md:mx-auto">');
				echo('
                    <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this image?\');">
                        <input type="hidden" name="destinationID" value="' . $destination->IDDestination . '" >
                            <button type="submit" name="deleteImage" class="mt-4 bg-red-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm hover:bg-red-700 transition cursor-pointer">Delete image</button>
                    </form>
                ');
			}
			
		?>
		<a href="my_destinations.php" class="mt-6 inline-block rounded-lg border border-travel-300 px-4 py-2 font-bold text-travel-800 hover:bg-travel-100 transition">Back to My Destinations</a>
		</section> 
		</main>
        <footer class="mt-10 border-t border-travel-200">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <p class="text-sm text-travel-700">© 2026 Hella Haraszti-Szabo. All rights reserved.</p>
            </div>
        </footer>	
	</body>
</html>