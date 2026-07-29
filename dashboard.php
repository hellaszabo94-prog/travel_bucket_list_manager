<?php
require ("includes/common.inc.php");
require ("includes/config.inc.php");

test($_POST);

session_start();

test($_SESSION["Emailadress"]);

if(count($_POST)>0){

    if(isset($_POST["Logout"])){

        $_SESSION=[];
    
    if(ini_get("session.use_cookies")){
        $parameter = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time()-86400,
            $parameter["path"],
            $parameter["domain"],
            $parameter["secure"],
            $parameter["httponly"],
        );
    }  
    session_destroy();
    }
}
if(!(isset($_SESSION["succesLogIn"]) && $_SESSION["succesLogIn"]===true)){
    header("Location: log.php");
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>Travel Bucket List Manager</title>
        <meta charset="utf_8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Travel Bucket List Manager</h1>
    <h2>Welcome to your personal Travel Bucket List Manager, 
        <?= htmlspecialchars($_SESSION["Emailadress"], ENT_QUOTES, "UTF-8") ?>!</h2>
    <form method="post">
        <input type="submit" value="Log out" name="Logout">
    </form>
    </body>
</html>