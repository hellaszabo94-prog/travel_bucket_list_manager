<?php 
    session_start();
    // remove all data stored in the session
    $_SESSION=[];
    // remove the session cookie if cookies are used
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
    // destroy the session completely
    session_destroy();
    // redirect the user to the login page
    header("Location: index.php");

    exit;

?>