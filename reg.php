<?php
require("includes/config.inc.php");
require("includes/common.inc.php");
require("includes/db.inc.php");

$conn = dbConnect();

test($_POST);

$msg ="";

if(count($_POST)>0){

    $email=trim($_POST["E"]);

    $sql = "
        SELECT
           COUNT(*) AS cnt
        FROM tbl_user
        WHERE(
            Emailaddress='" . $conn->real_escape_string($email). "'
        )
    ";

    test($sql);

    $dates = dbQuery($conn,$sql);

    test($dates);

    $newdates=dbFetch($dates);

    if($newdates->cnt==0){

        $pwd = trim($_POST["PWD"]);
		$pwd2 = trim($_POST["PWD2"]);

        if($pwd==$pwd2){

            if(strlen($pwd)>=8){
                $firstN=$conn->real_escape_string($_POST["FirstN"]);
                $lastN=$conn->real_escape_string($_POST["LastN"]);
                $birth=$conn->real_escape_string($_POST["Birth"]);
                $sql="
                    INSERT INTO tbl_user
                        (Emailaddress, Password, Firstname, Lastname, Birthdate)
                    VALUES (
                        '" . $conn->real_escape_string($_POST["E"]) . "',
                        '" . password_hash($pwd,PASSWORD_DEFAULT) . "',
                        " . trim($firstN) . ",
                        " . trim($lastN) . ",
                        " . trim($birth) . "    
                    )
                ";

                test($sql);

                $ok = dbQuery($conn,$sql);

                if($ok){
                    $msg='<p class="success"> Thank you! You are registered.</p>';
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


if(isset($_POST["Log in"])){
    
    header("Location: log.php");
}

?>
<!doctype html>
<html lang="en">
    <head>
        <title>Sing up</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Sing up</h2>
    
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