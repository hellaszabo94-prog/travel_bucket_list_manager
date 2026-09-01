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
                    $msg='<p class="success"> Thank you! You are registered. Now try to log in.</p>';
                }
            }
            else{
                $msg='<p class="error"> Password is too short.</p>';
            }
        }
        else{
            $msg='<p class="error"> Passwords do not match.</p>';
        } 
    }   
    else {
        $msg = '<p class="error">This email address is already registered. Please log in.</p>';
    }    
}

// log in buttton further on the log.php


if(isset($_POST["logButton"])){
    
    header("Location: index.php");

}

?>
<!doctype html>
<html lang="en">
    <head>
        <title>Sing up</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Sing up</h2>
    <?php echo($msg);?>
    <form method="post">
        <fieldset>
            <label>
                Email adresse:
                <input type="email" name="E">
            </label>
            <label>
                Password :
                <input type="password" name="PWD">
            </label>
            <label>
                Password 2:
                <input type="password" name="PWD2">
            </label>
            <label>
                First name:
                <input type="text" name="FirstN">
            </label>
            <label>
                Last name:
                <input type="text" name="LastN" >
            </label>
            <label>
                Birth date:
                <input type="date" name="Birth">
            </label>
        </fieldset>
        <input type="submit" value="Log in" name="logButton">
        <input type="submit" value="Sign up" name="regButton">
    </form>
    </body>
</html>