<?php
// load configuration, helper and database functions
require ("includes/config.inc.php");
require ("includes/common.inc.php");
require ("includes/db.inc.php");

// connect to the database
$conn = dbConnect();

//test($_POST);

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

	//test($userlist);

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
            $_SESSION["Emailaddress"] = $user->Emailaddress;
            $_SESSION["Firstname"] = $user->Firstname;

            // redirect the user to the dashboard
            header("Location: dashboard.php");
            exit;
    }
    else{
        $msg='<p class="error">Invalid email or password.</p>';
    }
              
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/tailwind.css">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body class="min-h-screen bg-gradient-to-br from-travel-50 via-white to-travel-100 text-travel-950">
        <main class="min-h-screen flex items-center justify-center px-4 py-8">
            <section class="w-full max-w-md rounded-xl border border-travel-100 bg-white p-6 shadow-sm">
                <h1 class="font-display text-center text-3xl font-bold bg-gradient-to-r from-travel-900 to-travel-500 bg-clip-text text-transparent">Travel Bucket List Manager</h1>
                <p class="mt-2 text-center text-travel-700">Log in to manage your travel bucket list.</p>
                <?php echo($msg);?>
                <div class="mt-6">
                    <form method="post">
                        <label class="font-display block font-bold text-travel-800">
                            Email adresse:
                            <input type="email" name="E" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30">
                        </label>
                        <label class="font-display block font-bold text-travel-800">
                            Password:
                            <input type="password" name="PWD" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30">
                        </label>
                        <br>
                        <input type="submit" value="Log in" name="logButton" class="mt-6 w-full rounded-lg bg-travel-800 px-5 py-2.5 font-bold text-white shadow-sm hover:ring-4 hover:ring-travel-500/40 transition-all duration-200 cursor-pointer">
                        <p class="mt-6 text-center text-sm text-travel-700">Don't have an account?
                            <a href="reg.php"class="font-bold text-travel-800 hover:text-travel-600 underline-offset-4 hover:underline transition">Create an account</a>
                    </form>
                </div>    
            </section>
    </body>
</html>