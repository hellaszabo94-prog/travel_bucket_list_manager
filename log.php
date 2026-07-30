<?php
// load configuration, helper and database functions
require("includes/config.inc.php");
require("includes/common.inc.php");
require("includes/db.inc.php");

// connect to the database
$conn = dbConnect();

test($_POST);

$msg="";

// run login process after clicking the login button
if(isset($_POST["logButton"])>0){

    $logemail=trim($_POST["E"]);

    // search for the user by email address

	$sql = "
		SELECT
			*
		FROM tbl_user
		WHERE(
			Emailaddress='" . $conn->real_escape_string($logemail) . "'
		)
	";

	//test($sql);

	$userlist = dbQuery($conn,$sql);

	test($userlist);

    // get the user data from the query result
    $user = dbFetch($userlist);

    // check if the user exists and the password is correct
	if($user && password_verify($_POST["PWD"],$user->Password)) {

            // start the session and create a new session ID
            session_start();
            session_regenerate_id(true);

            // save the logged-in user's data in the session
            $_SESSION["successLogin"] = true;
            $_SESSION["userID"] = $user->IDUser;
            $_SESSION["Emailadress"] = $logemail;

            // redirect the user to the dashboard
            header("Location: dashboard.php");
            exit;
    }
    else{
        $msg='<p class="error">Invalid email or password.</p>';
    }
              
}

// redirect to the registration page
if(isset($_POST["regButton"])){

    header("Location: reg.php");
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf_8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Log in</h2>
    <?php echo($msg);?>
    <form method="post">
        <label>
            Email adresse:
            <input type="email" name="E">
        </label>
        <label>
            Password:
            <input type="password" name="PWD">
        </label>
        <br>
        <input type="submit" value="Log in" name="logButton">
        <input type="submit" value="Sign up" name="regButton">
    </form>
    </body>
</html>