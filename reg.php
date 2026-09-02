<?php
// load configuration, helper functions and database functions
require("includes/config.inc.php");
require("includes/common.inc.php");
require("includes/db.inc.php");

// create database connection
$conn = dbConnect();

//test($_POST);

$msg ="";

// registration process after email control
if(count($_POST)>0){

    $email=trim($_POST["E"]);

    // check the email address is already registered

    $sql = "
        SELECT
           COUNT(*) AS cnt
        FROM tbl_user
        WHERE(
            Emailaddress='" . $conn->real_escape_string($email). "'
        )
    ";

    //test($sql);

    $dates = dbQuery($conn,$sql);

    //test($dates);

    $newdates=dbFetch($dates);

    // continue registration if email address does not exist

    if($newdates->cnt==0){

        $pwd = trim($_POST["PWD"]);
		$pwd2 = trim($_POST["PWD2"]);

        // check both password fields are same 

        if($pwd==$pwd2){

            // password has to be 8 characters

            if(strlen($pwd)>=8){

                $firstN=$conn->real_escape_string($_POST["FirstN"]);
                $lastN=$conn->real_escape_string($_POST["LastN"]);
                $birth=$conn->real_escape_string($_POST["Birth"]);

                // insert the new user 

                $sql="
                    INSERT INTO tbl_user
                        (Emailaddress, Password, Firstname, Lastname, Birthdate)
                    VALUES (
                        '" . $conn->real_escape_string($_POST["E"]) . "',
                        '" . password_hash($pwd,PASSWORD_DEFAULT) . "',
                        '" . trim($firstN) . "',
                        '" . trim($lastN) . "',
                        '" . trim($birth) . "'    
                    )
                ";

                //test($sql);

                $ok = dbQuery($conn,$sql);

                // display a confirmation message after successful registration

                if($ok){
                    $msg='<p class="mt-4 rounded-lg border border-travel-200 bg-travel-50 px-4 py-3 text-travel-800"> Thank you! You are registered. Now try to log in.</p>';
                }
            }
            else{
                $msg='<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700"> Password is too short.</p>';
            }
        }
        else{
            $msg='<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700"> Passwords do not match.</p>';
        } 
    }   
    else {
        $msg = '<p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">This email address is already registered. Please log in.</p>';
    }    
}


?>
<!doctype html>
<html lang="en">
    <head>
        <title>Sing up</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/tailwind.css">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body class="min-h-screen bg-gradient-to-br from-travel-50 via-white to-travel-100 text-travel-950">
        <main class="min-h-screen flex items-center justify-center px-4 py-8">
            <section class="w-full max-w-lg rounded-xl border border-travel-100 bg-white p-6 shadow-sm">  
                <h1 class="font-display text-center text-3xl font-bold bg-gradient-to-r from-travel-900 to-travel-500 bg-clip-text text-transparent">Travel Bucket List Manager</h1>
                <h2 class="mt-6 text-center font-display text-3xl font-bold text-travel-900">Create your account</h2>
                <p class="mt-2 text-center text-travel-700">Create an account to start building your personal travel bucket list.</p>
                <?php echo($msg);?>
                <form method="post">
                    <fieldset>
                        <div class="mt-4">
                            <label class="block font-display font-bold text-travel-800">
                                Email address:
                                <input type="email" name="E" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" required>
                            </label>
                        </div>
                        <div class="mt-4">              
                            <label class="block font-display font-bold text-travel-800">
                                Password :
                                <input type="password" name="PWD" name="password" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" required>
                            </label>
                        </div>  
                        <div class="mt-4">      
                            <label class="block font-display font-bold text-travel-800">
                                Confirm password:
                                <input type="password" name="PWD2" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" required>
                            </label>
                        </div>     
                        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2"> 
                            <div>  
                                <label class="block font-display  font-bold text-travel-800">
                                    First name:
                                    <input type="text" name="FirstN" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" required>
                                </label>
                            </div>     
                            <div>    
                                <label class="block font-display  font-bold text-travel-800">
                                    Last name:
                                    <input type="text" name="LastN" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" required>
                                </label>
                            </div>     
                        </div>
                        <div class="mt-4">         
                            <label class="block font-bold text-travel-800">
                                Birth date:
                                <input type="date" name="Birth" class="mt-2 w-full rounded-lg border border-travel-200 px-3 py-2.5 focus:outline-none focus:border-travel-700 focus:ring-2 focus:ring-travel-700/30" required>
                            </label>
                        </div>        
                    </fieldset>
                    <input type="submit" value="Create account" name="regButton"  class="mt-6 w-full rounded-lg bg-travel-800 px-5 py-2.5 font-bold text-white shadow-sm hover:ring-4 hover:ring-travel-500/40 transition-all duration-200 cursor-pointer">
                    <p class="mt-6 text-center text-sm text-travel-700">Already have an account?
                        <a href="index.php" class="font-bold text-travel-800 hover:text-travel-600 underline-offset-4 hover:underline transition">Log in</a>
                    </p>
                </form>
            </section>
        </main>
    </body>
</html>